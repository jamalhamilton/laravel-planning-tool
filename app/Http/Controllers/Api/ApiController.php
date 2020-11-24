<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use App\Models\CoreRightUser;
use App\Models\CoreRightUserStatus;
use App\Models\CoreRightUserGroup;
use App\Models\CoreRightGroup;

use App\Models\Client;
use App\Models\ClientConService;
use App\Models\ClientConServiceValue;

use App\Models\Campaign;
use App\Models\CampaignChannel;
use App\Models\CampaignChannelVersion;
use App\Models\CoreServiceGroup;
use App\Models\CoreServiceGroupItem;
use App\Models\CampaignChannelParameter;
use App\Models\CampaignChannelDistribution;
use App\Models\CoreCampaignStatus;

use App\Models\CoreCampaignCategory;
use App\Models\CampaignChannelMediaConCategory;
use App\Models\CoreRegion;
use App\Models\CampaignChannelMedia;
use App\Models\SCoreAdFormat;
use App\Models\CampaignChannelMediaNote;
use App\Models\CampaignServiceGroupOrder;
class ApiController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
       //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function users(Request $request)
    {
        $tokenKey = $request->tokenKey;
        $hasAPI = CoreRightUser::where('hasAPI',1)->where('tokenKey',$tokenKey)->count();

        if ($hasAPI == 0) {
            $resp = array(
                'code' => 403,
                'msg' => 'You have not permission or API token is invalid.',
                'data' => array()
            );

            return response()->json($resp);
        }

       
        $coreUsers = CoreRightUser::all();

        foreach ($coreUsers as $coreUser) {
            $id = $coreUser->ID;
            $picture = $coreUser->picture;
            $firstname = $coreUser->firstname;
            $lastname = $coreUser->lastname;
            $initial = $coreUser->initials;
            $email = $coreUser->email;
            $hasAPI = $coreUser->hasAPI;
            $tokenKey = $coreUser->tokenKey;

            if ($coreUser->corerightusergroup) {
                $group = $coreUser->corerightusergroup->corerightgroup->name;
            } else {
                $group = 'Adminitrator';
            }

            if ($coreUser->corerightuserstatus) {
                $status = $coreUser->corerightuserstatus->status;
            } else {
                $status = 'Inaktiv';
            }

            $data[] = array(
                'picture' => $picture,
                'firstname' => $firstname,
                'lastname' => $lastname,
                'initial' => $initial,
                'email' => $email,
                'tokenKey' => $tokenKey,
                'group' => $group,
                'status' => $status,
                'ID' => $id,
            );
        }

        $resp = array(
            'code' => 200,
            'msg' => '',
            'data' => $data
        );

        return response()->json($resp);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function clients(Request $request)
    {
        $tokenKey = $request->tokenKey;
        $hasAPI = CoreRightUser::where('hasAPI',1)->where('tokenKey',$tokenKey)->count();

        if ($hasAPI == 0) {
            $resp = array(
                'code' => 403,
                'msg' => 'You have not permission or API token is invalid.',
                'data' => array()
            );

            return response()->json($resp);
        }

        $clients = Client::all();

        foreach ($clients as $client) {
            $contactInfo = array(
                'clientID' => $client->ID,
                'clientName' => $client->name,
                'clientStreet' => $client->address,
                'clientPostcode' => $client->zip,
                'clientState' => $client->city,
                'clientLogo' => $client->logo
            );

            $query = "select t2.ID, t1.ID as serviceID, t1.groupType, t1.name, t1.defaultValue, t2.value 
                    from clients_con_services t1 
                    left join clients_con_services_values t2 on (t1.ID = t2.conServiceID and t2.clientID = ?) 
                    where t1.clientID = ? or t1.isConstant = 1"; 

            $result = DB::select($query, [$client->ID, $client->ID]);
            $hourlyServices = array();
            $fixedServices = array();
            $percentualServices = array();

            foreach ($result as $item) {
                
                if ($item->groupType == 'HOURLY_RATE') {
                    array_push($hourlyServices, $item);
                }   
                else if ($item->groupType == 'FIXED_RATE') {
                    array_push($fixedServices, $item);
                }
                else if ($item->groupType == 'PERCENTUAL_RATE') {
                    array_push($percentualServices, $item);
                } 

            }
            
            $costInfo = array(
                'hourlyServices' => $hourlyServices,
                'fixedServices' => $fixedServices,
                'percentualServices' => $percentualServices
            );
           
           $data[] = array(
                    'contactInfo' => $contactInfo,
                    'costInfo' => $costInfo
                );
            
        }

        $resp = array(
            'code' => 200,
            'msg' => '',
            'data' => $data
        );

        return response()->json($resp);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function campaigns(Request $request)
    {
        $tokenKey = $request->tokenKey;
        $hasAPI = CoreRightUser::where('hasAPI',1)->where('tokenKey',$tokenKey)->count();

        if ($hasAPI == 0) {
            $resp = array(
                'code' => 403,
                'msg' => 'You have not permission or API token is invalid.',
                'data' => array()
            );

            return response()->json($resp);
        }

        $campaigns = Campaign::all();

        $index = 0;
        $data = array();
        foreach ($campaigns as $campaign) {
            $campaignID = $campaign->ID;

            $campaign = Campaign::where('ID', $campaignID)->first();
            $customerID = -1;

            if ($campaign) {
                $customerID = $campaign->clientID;
            }

            // calculate overview values for all channels

            $channels = CampaignChannel::where('campaignID', $campaignID)->get();
            $serviceGroups = CoreServiceGroup::where('isConstant',1)->get()->toArray();
            $serviceCosts = array();
            $serviceCosts_channel = array();
            $serviceTotal = 0;
            $rates = $this->getRates($customerID);
            $serviceCosts_channel_tmp = array();

            foreach ($channels as $channel) {
                $onlineChannel = CampaignChannel::where('name',$channel->name)->where('campaignID', $campaignID)->first();
                $onlineChannelID = -1;

                if ($onlineChannel) {
                    $onlineChannelID = $onlineChannel->ID;
                }

                // Calculate service costs

                $adP = $this->calculateadPSum($campaignID, $channel->name);
                $NNInChf = $this->calculateNNInChfSum($campaignID, $channel->name);

                if(isset($rates['FIXED_RATE'][0]->value)){
                    $adP =  array_values($rates['FIXED_RATE'])[0]->value * floatval($adP) * 0.001;
                }
                if(isset($rates['PERCENTUAL_RATE'][0]->value)){
                    $NNInChf = array_values($rates['PERCENTUAL_RATE'])[0]->value * floatval($NNInChf) / 100;
                }
                $serviceCosts_channel[$channel->name] = array();

                foreach ($serviceGroups as $serviceGroup) {
                    $query = "select SUM(t3.calcValue) as subTotal
                        from core_service_groups_items t1
                        left join campaign_channels_parameters t3 on t1.ID = t3.serviceItemID
                        where t3.channelID = ? and t1.groupID = ? group by t1.groupID";

                    $res = DB::select($query, [$onlineChannelID, $serviceGroup['ID']]);
                    $subTotal = (count($res) > 0) ? $res[0]->subTotal : "0.00";

                    if($serviceGroup["name"] == 'Technische Kosten' && count($res) == 0 && $channel->name == 'online') {
                        $NNInChf = $this->calculateadPSum($campaignID, $channel->name);
                        $selectRates = $this->getRatesKosten_Honorar($customerID);
                        $subTotal = $NNInChf * $selectRates['Technische_Kosten'] / 1000;
                    }
                    if($serviceGroup["name"] == 'Media-Honorar' && count($res) == 0 && $channel->name == 'online') {
                        $AdP = $this->calculateNNInChfSum($campaignID, $channel->name);
                        $selectRates = $this->getRatesKosten_Honorar($customerID);
                        $subTotal = $AdP * $selectRates['Media_Honorar']/100;
                    }
                    $cost = array(
                        'groupName' => $serviceGroup["name"],
                        'subTotal' => floatval($subTotal)
                    );

                    $serviceCosts_channel_tmp[$serviceGroup["name"]] = 0;

                    $serviceTotal += floatval($subTotal);

                    array_push($serviceCosts_channel[$channel->name] , $cost);
                }
            }

            foreach ($serviceCosts_channel as $chn_name => $costs_ar) {
                foreach($costs_ar as $cost){
                    $serviceCosts_channel_tmp[$cost['groupName']] += $cost['subTotal'];
                }
            }

            foreach ($serviceCosts_channel_tmp as $groupNm => $value) {
                $cost = array(
                    'groupName' =>$groupNm,
                    'subTotal' => $value
                );
                array_push($serviceCosts, $cost);
            }

            ////////////////////

            // Collect campaign information
            $campaignName = $campaign->name;
            $customerName = $campaign->clients->name;

            $versions = array();
            $mediaCosts = array();
            $mediaTotal = 0;
            $nnCHFTotal = 0;
            $totalStartTime = 9000000000;
            $totalEndTime = 0;

            foreach($channels as $channel) {
                $starttime = strtotime($channel->startDate);
                $endtime = strtotime($channel->endDate);

                if (($channel->name == 'online' || $channel->name == 'ambient') && $channel->hasExtraWeek == 1) {
                    $calendarDate = date_create($channel->endDate);
                    date_add($calendarDate,date_interval_create_from_date_string("7 days"));

                    if ($endtime < strtotime(date_format($calendarDate,"Y-m-d"))) {
                        $endtime = strtotime(date_format($calendarDate,"Y-m-d"));
                    }
                }

                if ($starttime < $totalStartTime) {
                    $totalStartTime = $starttime;
                }

                if ($endtime > $totalEndTime) {
                    $totalEndTime = $endtime;
                }

                $cost = array();
                $version = CampaignChannelVersion::where('channelID', $channel->ID)->orderBy('changedDateTime','desc')->first();

                if ($version) {
                    $formattedDate = date("d.m.Y", strtotime($version->changedDateTime));
                    $channelVersion = $version->versionNumber;
                } else {
                    $formattedDate = date("d.m.Y",time());
                    $channelVersion = "V 1.0";
                }

                $channelVersionInfo = array(
                    'channelName' => $channel->name,
                    'channelLatestVersion' => $channelVersion,
                    'channelVersionDate' => $formattedDate
                );

                array_push($versions, $channelVersionInfo);

                // Pre collect media data
                $query = "select SUM(t1.adPressureValue) as subTotal, SUM(t1.nnCHF) as nnCHF
                        from campaign_channels_media t1
                        left join campaign_channels_media_con_categories t2
                        on t1.categoryID = t2.ID
                        where t2.channelID = ?";

                $media = DB::select($query,[$channel->ID]);

                $subTotal = (count($media) > 0) ? $media[0]->subTotal : "0.00";
                $nnCHF = (count($media) > 0) ? $media[0]->nnCHF : "0.00";
                $cost = array(
                    'channelName' => $channel->name,
                    'subTotal' => floatval($subTotal),
                    'nnCHF' => floatval($nnCHF)
                );

                $mediaTotal += floatval($subTotal);
                $nnCHFTotal += floatval($nnCHF);
                array_push($mediaCosts, $cost);
            }

            if ($totalStartTime == 9000000000) {
                $totalStartTime = time();
            }

            if ($totalEndTime == 0) {
                $totalEndTime = time();
            }

            $currentTime = time();
            $status = $campaign->statusID;
            if (($status >= 2) && ($totalStartTime < $currentTime) && ($totalEndTime > $currentTime)) {
                $campaign->statusID = 3;
                $campaign->save();
                $status = $campaign->statusID;
            }

            if (($status >= 2) && ($totalEndTime > $currentTime)) {
                $campaign->statusID = 2;
                $campaign->save();
                $status = $campaign->statusID;
            }

            $total = floatval($nnCHFTotal) + floatval($serviceTotal);  // total value of calculate
            // calculate service cost percentage
            foreach($serviceCosts as $key => $serviceCost) {
                if ($serviceTotal == 0) {
                    $serviceCosts[$key]["percentage"] = "0.00";

                } else {
                    $serviceCosts[$key]["percentage"] = number_format(floatval($serviceCost["subTotal"]) / floatval($total) * 100, 2);
                }
                $serviceCosts[$key]["subTotal"] = number_format($serviceCost["subTotal"], 2,".","'");
            }
            $serviceData = array(
                'total' => number_format(floatval($serviceTotal),2,".","'"),
                'serviceCosts' => $serviceCosts
            );

            //
            $statuses = CoreCampaignStatus::all()->toArray();
            foreach ($mediaCosts as $key => $mediaCost) {
                if ($nnCHFTotal == 0) {
                    $mediaCosts[$key]["percentage"] = "0.00";
                } else {
                    if($nnCHFTotal != 0)
                        $mediaCosts[$key]["percentage"] = number_format(floatval($mediaCost["nnCHF"]) / floatval($total) * 100, 2);
                    else
                        $mediaCosts[$key]["percentage"] = 0;
                }

                $mediaCosts[$key]["subTotal"] = number_format($mediaCost["nnCHF"], 2,".","'");
            }

            $mediaData = array(
                "total" => number_format(floatval($nnCHFTotal), 2,".","'"),
                "mediaCosts" => $mediaCosts
            );

            $vat = config('constant.VAT');
            $totalMWST = ($total * (100 + $vat)) / 100;

            if ($total == 0) {
                $serviceData["percentage"] = "0.00";
                $mediaData["percentage"] = "0.00";
            } else {
                $serviceData["percentage"] = number_format(floatval($serviceTotal) / floatval($total) * 100, 2);
                $mediaData["percentage"] = number_format(floatval($nnCHFTotal) / floatval($total) * 100, 2);
            }


            $data[] = array(
                'campaignID' => $campaignID,
                'campaignName' => $campaignName,
                'customerName' => $customerName,
                'channels' => $versions,
                'totalStartdate' => date("d.m.Y", $totalStartTime),
                'totalEnddate' => date("d.m.Y", $totalEndTime),
                'planningStatus' => $status,
                'statuses' => $statuses,
                'costData' => array(
                    'serviceData' => $serviceData,
                    'mediaData' => $mediaData,
                    'total' => number_format($total, 2),
                    'totalMWST' => number_format($totalMWST, 2)
                )
            );
        }

        $resp = array(
            'code' => 200,
            'msg' => '',
            'data' => $data
        );

        return response()->json($resp);


        }



    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function params(Request $request)
    {
        $tokenKey = $request->tokenKey;
        $campaignID = $request->campaignID;
        $active_channel = $request->channel;

        $hasAPI = CoreRightUser::where('hasAPI',1)->where('tokenKey',$tokenKey)->count();

        if ($hasAPI == 0) {
            $resp = array(
                'code' => 403,
                'msg' => 'You have not permission or API token is invalid.',
                'data' => array()
            );

            return response()->json($resp);
        }

        if (empty($campaignID)) {
            $resp = array(
                'code' => 404,
                'msg' => 'You must send request with campaign id - {campaignID}.',
                'data' => array()
            );

            return response()->json($resp);
        }

        if (empty($active_channel)) {
            $resp = array(
                'code' => 404,
                'msg' => 'You must send request with channel name  - {channel}.',
                'data' => array()
            );

            return response()->json($resp);
        }


        $campaign = Campaign::where('ID', $campaignID)->first();
        $customerID = -1;
        $campaignName = '';

        if ($campaign) {
            $customerID = $campaign->clientID;
            $campaignName = $campaign->name;
        }

        $channel = CampaignChannel::where('name',$active_channel)->where('campaignID', $campaignID)->first();
        $channelID = -1;

        if ($channel) {
            $channelID = $channel->ID;
        } else {
            $resp = array(
                'code' => 404,
                'msg' => 'The channel doesnt exist. Try again with different channel param - {channel}.',
                'data' => array()
            );

            return response()->json($resp);
        }

        $serviceGroups = CoreServiceGroup::where('isConstant',1)->orWhere('channelID', $channelID)->get()->toArray();
        $serviceGroupsOrders = CampaignServiceGroupOrder::where('channelID', $channelID)->get()->toArray();
        $serviceGroupOrdersCnt = count($serviceGroupsOrders);
        $orderMap = array();

        $orderedServiceGroups = array();

        if ($serviceGroupOrdersCnt > 0) {
            foreach ($serviceGroupsOrders as $serviceGroupOrder) {
                $orderMap[$serviceGroupOrder['groupID']] = $serviceGroupOrder['sortOrder'];
            }

            foreach ($serviceGroups as $k=>$serviceGroup) {
                $serviceGroup['sortOrder'] = isset($orderMap[$serviceGroup['ID']])?$orderMap[$serviceGroup['ID']]:$k+1;

                $index = isset($orderMap[$serviceGroup['ID']])? $orderMap[$serviceGroup['ID']] - 1:$k;
                $orderedServiceGroups[$index] = $serviceGroup;
            }

            ksort($orderedServiceGroups);
            $serviceGroups = $orderedServiceGroups;
        }

        $parameters = array();

        $selectRates = $this->getRates($customerID);
        $selectRates_GroupWithName = $this->getRates_GroupWithName($customerID);

        foreach ($serviceGroups as $order => $serviceGroup) {
            $serviceGroupItems = CoreServiceGroupItem::whereRaw('(groupID = ? and isConstant = 1) or (groupID = ? and channelID = ?)',[$serviceGroup['ID'],$serviceGroup['ID'],$channelID])->orderBy('sortOrder', 'ASC')->get()->toArray();

            if ($serviceGroupOrdersCnt == 0)
                $serviceGroup['sortOrder'] = $order + 1;

            $serviceGroup['children'] = array();
            $serviceGroup['isEmpty'] = (count($serviceGroupItems) == 0);

            foreach ($serviceGroupItems as $k => $serviceGroupItem) {



                $serviceParam = array(
                    'paramID' => '',
                    'itemID' => $serviceGroupItem['ID'],
                    'groupID' => $serviceGroup['ID'],
                    'itemName' => $serviceGroupItem['name'],
                    'itemValue' => 0,
                    'itemType' => $serviceGroupItem['value'],
                    'csID' => -1,
                    'calcValue' => "0.00",
                    'isFlatrate' => 0,
                    'isConstant' => $serviceGroupItem['isConstant'],
                    'sortOrder' => $k+1,
                );

                $param = CampaignChannelParameter::where('channelID', $channelID)->where('serviceItemID', $serviceGroupItem['ID'])->first();

                if ($param) {
                    $serviceParam['paramID'] = $param->ID;
                    $serviceParam['csID'] = $param->clientServiceItemID;
                    $serviceParam['itemValue'] = $param->value;
                    $serviceParam['calcValue'] = number_format($param->calcValue, 2,'.','');
                    $serviceParam['isFlatrate'] = $param->isFlatrate;

                }

                array_push($serviceGroup['children'], $serviceParam);
            }


            array_push($parameters, $serviceGroup);
        }

/*        $NNInChf = $this->calculateNNInChfSum($campaignID, $active_channel);
        $AdP = $this->calculateadPSum($campaignID, $active_channel);

        $NNInChf = number_format($NNInChf, 2,".","'");
        $AdP = number_format($AdP, 2,".","'");*/

        $resp = array(
            'code' => 200,
            'msg' => '',
            'data' => $parameters
        );

        return response()->json($resp);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function medias(Request $request)
    {
        $tokenKey = $request->tokenKey;
        $campaignID = $request->campaignID;
        $active_channel = $request->channel;

        $hasAPI = CoreRightUser::where('hasAPI',1)->where('tokenKey',$tokenKey)->count();

        if ($hasAPI == 0) {
            $resp = array(
                'code' => 403,
                'msg' => 'You have not permission or API token is invalid.',
                'data' => array()
            );

            return response()->json($resp);
        }

        if (empty($campaignID)) {
            $resp = array(
                'code' => 404,
                'msg' => 'You must send request with campaign id - {campaignID}.',
                'data' => array()
            );

            return response()->json($resp);
        }

        if (empty($active_channel)) {
            $resp = array(
                'code' => 404,
                'msg' => 'You must send request with channel name - {channel}.',
                'data' => array()
            );

            return response()->json($resp);
        }

        $currentChannel = CampaignChannel::where('campaignID' ,$campaignID)->where('name',$active_channel)->first();
        $channelID = 0;

        if ($currentChannel) {
            $channelID = $currentChannel->ID;
        } else {
            $resp = array(
                'code' => 404,
                'msg' => 'The channel doesnt exist. Try again with different channel name - {channel}.',
                'data' => array()
            );

            return response()->json($resp);
        }

        $currentCategory = CampaignChannelMediaConCategory::where('channelID',$channelID)->get();
        $currentCategories = array();


        foreach ($currentCategory as $ctg) {
            $currentCategories[$ctg->categoryID] = CoreCampaignCategory::where('ID',$ctg->categoryID)->where('channel_name', $active_channel)->first();
        }

        $constantCategories = CoreCampaignCategory::where('isConstant','1')->where('channel_name',$active_channel)->get();
        $categories = CampaignChannel::where('campaignID',$campaignID)->where('name',$active_channel)->first()->campaignChannelmediaconcategory;
        $data = array();

        if($active_channel == 'plakat'){
            $regions = CoreRegion::all();
        }
        else{
            $regions = CoreRegion::where('name','!=' , 'D-/F-CH')->where('name','!=' , 'National')->get();
        }

        $calRegions = array();
        foreach ($categories as $category) {

            $categoryID = $category->ID;
            $cateItem = CoreCampaignCategory::where('ID',$category->categoryID)->where('channel_name', $active_channel)->first();
            if(isset($cateItem->isConstant)) {
                $isConstant = $cateItem->isConstant;

                $categoryName = $category->corecampaigncategory->name;
                $categoryNote = $category->categoryNote;
                $medias = $category->campaignchannelmedia;

                $mediaData = array();

                $index = 0;
                foreach ($medias as $media) {
                    $notes = array();

                    foreach ($media->campaignchannelmedianote as $note) {
                        $notes[$note->colOrder] = $note->note;
                    }


                    $mediaData[$index] = array(
                        'id' => $media->ID,
                        /* 'region' => $media->coreregion->name,*/
                        'placement' => $media->placing,
                        'details' => $media->details,
                        'adPrint' => number_format($media->adPressureValue, 0, '.', '\''),
                        /*'format' => $media->scoreadformat->name,*/
                        'tkpGrossCHF' => number_format($media->tkpGrossCHF, 2, '.', '\''),
                        'grossCHF' => number_format($media->grossCHF, 2, '.', '\''),
                        'discountPersentual' => number_format($media->discountPersentual, 2, '.', '\''),
                        'netCHF' => number_format($media->netCHF, 2, '.', '\''),
                        'bkPersentual' => number_format($media->bkPersentual, 2, '.', '\''),
                        'nnCHF' => number_format($media->nnCHF, 2, '.', '\''),
                        'tkpNNCHF' => number_format($media->tkpNNCHF, 2, '.', '\''),
                        'rowOrder' => number_format($media->rowOrder, 2, '.', '\''),
                        'mediaNotes' => $notes,
                        'format' => $media->formatValue,
                        'grps' => number_format($media->grps, 2, '.', '\''),
                        'kontaktsumme' => number_format($media->kontaktsumme, 0, '.', '\''),
                    );
                    if ($media->regionID) {

                        $tmp = explode(', ', $media->regionID);
                        $coreregion_name = [];
                        foreach ($tmp as $tmpid){
                            $rtemp = CoreRegion::where('ID', intval($tmpid))->first();
                            if($rtemp){
                                $coreregion_name[] = $rtemp['name'];
                            }

                        }
                        $mediaData[$index]['region'] = implode(', ',$coreregion_name);
                    } else {
                        $mediaData[$index]['region'] = "";
                    }
                    if(sizeof($coreregion_name) > 0) {
                        $calRegions[$coreregion_name[0]] = 1;
                    }
                    $index++;
                }

                $data[] = array(
                    'id' => $categoryID,
                    'isConstant' => $isConstant,
                    'name' => $categoryName,
                    'note' => $categoryNote,
                    'media' => $mediaData,
                );
            }
        }

        $customerID = -1;

        $campaign = Campaign::where('ID', $campaignID)->first();
        if ($campaign) {
            $customerID = $campaign->clientID;
        }

        $resp = array(
            'code' => 200,
            'msg' => '',
            'data' => $data,
            'calRegions' => $calRegions,
            'constantCategories' => $constantCategories,
            'currentCategories' => $currentCategories,
            'regions' => $regions,
            'clientID' => $customerID,
        );

        return response()->json($resp);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    protected function getRates($customerID = 0)
    {
        $query = "select t2.ID, t1.ID as serviceID, t1.groupType, t1.name, t1.defaultValue, t2.value
                from clients_con_services t1
                left join clients_con_services_values t2 on (t1.ID = t2.conServiceID and t2.clientID = ?)
                where t1.clientID = ? or t1.isConstant = 1 group by value";

        $result = DB::select($query, [$customerID, $customerID]);
        $hourlyServices = array();
        $fixedServices = array();
        $percentualServices = array();

        $flag = 0;
        foreach($result as $item) {
            if(!isset($item->value))
                $item->value = 0;

            if ($item->groupType == 'HOURLY_RATE') {
                array_push($hourlyServices, $item);
            } else if ($item->groupType == 'FIXED_RATE') {
                if($item->name == 'Traffic-Kosten') {
                    array_push($fixedServices, $item);
                }
            } else if ($item->groupType == 'PERCENTUAL_RATE') {
                if($item->name == 'Honorar Auf Media n/n') {
                    $flag = 1;
                    array_push($percentualServices, $item);
                }
            }
        }

        if($flag == 0){
            $item->name = 'Honorar Auf Media n/n';
            $item->value = 0;
            array_push($percentualServices, $item);
        }
        $services = array(
            'HOURLY_RATE' => $hourlyServices,
            'FIXED_RATE' => $fixedServices,
            'PERCENTUAL_RATE' => $percentualServices
        );

        return $services;
    }

    protected function getRatesKosten_Honorar($customerID = 0)
    {
        $query = "select t2.ID, t1.ID as serviceID, t1.groupType, t1.name, t1.defaultValue, t2.value
                from clients_con_services t1
                left join clients_con_services_values t2 on (t1.ID = t2.conServiceID and t2.clientID = ?)
                where t1.clientID = ? or t1.isConstant = 1 group by value";

        $result = DB::select($query, [$customerID, $customerID]);
        $Technische_Kosten = 0;
        $Media_Honorar = 0;

        foreach($result as $item) {
            if($item->name == 'Traffic-Kosten') {
                $Technische_Kosten = $item->value;
            }
            if($item->name == 'Honorar Auf Media n/n') {
                $Media_Honorar = $item->value;
            }
        }

        $services = array(
            'Technische_Kosten' => $Technische_Kosten,
            'Media_Honorar' => $Media_Honorar,
        );

        return $services;
    }

    protected function getRates_GroupWithName($customerID = 0)
    {
        $query = "select t2.ID, t1.ID as serviceID, t1.groupType, t1.name, t1.defaultValue, t2.value
                from clients_con_services t1
                left join clients_con_services_values t2 on (t1.ID = t2.conServiceID and t2.clientID = ?)
                where t1.clientID = ? or t1.isConstant = 1 group by value,name";

        $result = DB::select($query, [$customerID, $customerID]);
        $hourlyServices = array();
        $fixedServices = array();
        $percentualServices = array();

        foreach($result as $item) {
            if ($item->groupType == 'HOURLY_RATE') {
                array_push($hourlyServices, $item);
            } else if ($item->groupType == 'FIXED_RATE') {
                if($item->name == 'Traffic-Kosten') {
                    array_push($fixedServices, $item);
                }
            } else if ($item->groupType == 'PERCENTUAL_RATE') {
                if($item->name == 'Honorar Auf Media n/n') {
                    array_push($percentualServices, $item);
                }
            }
        }

        $services = array(
            'HOURLY_RATE' => $hourlyServices,
            'FIXED_RATE' => $fixedServices,
            'PERCENTUAL_RATE' => $percentualServices
        );

        return $services;
    }


    public function calculateNNInChfSum($campaignID, $channel_name){


        $currentChannel = CampaignChannel::where('campaignID' ,$campaignID)->where('name',$channel_name)->first();

        if ($currentChannel) {
            $channelID = $currentChannel->ID;
        } else {
            return redirect('planning/overview?id='.$campaignID);
        }

        $currentCategory = CampaignChannelMediaConCategory::where('channelID',$channelID)->get();
        $currentCategories = array();

        foreach ($currentCategory as $ctg) {
            $currentCategories[$ctg->categoryID] = CoreCampaignCategory::where('ID',$ctg->categoryID)->first();
        }

        $categories = CampaignChannel::where('campaignID',$campaignID)->where('name',$channel_name)->first()->campaignChannelmediaconcategory;

        $sum = 0;

        foreach ($categories as $category) {

            $medias = $category->campaignchannelmedia;

            foreach ($medias as $media) {
                $sum += $media ->nnCHF;
            }

        }

        return number_format($sum, 2, '.', '\'');
    }

    public function calculateadPSum($campaignID,$channel_name){


        $currentChannel = CampaignChannel::where('campaignID' ,$campaignID)->where('name',$channel_name)->first();

        if ($currentChannel) {
            $channelID = $currentChannel->ID;
        } else {
            return redirect('planning/overview?id='.$campaignID);
        }

        $currentCategory = CampaignChannelMediaConCategory::where('channelID',$channelID)->get();
        $currentCategories = array();

        foreach ($currentCategory as $ctg) {
            $currentCategories[$ctg->categoryID] = CoreCampaignCategory::where('ID',$ctg->categoryID)->first();
        }

        $categories = CampaignChannel::where('campaignID',$campaignID)->where('name',$channel_name)->first()->campaignChannelmediaconcategory;

        $sum = 0;

        foreach ($categories as $category) {

            $medias = $category->campaignchannelmedia;

            foreach ($medias as $media) {
                $sum += $media ->adPressureValue;
            }

        }

        return number_format($sum, 2, '.', '\'');
    }
}
