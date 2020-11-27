<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\CoreCampaignCategory;
use App\Models\Campaign;
use App\Models\CampaignChannel;
use App\Models\CampaignChannelVersion;
use App\Models\CampaignChannelMediaConCategory;
use App\Models\CampaignExport;
use App\Models\CoreServiceGroup;
use App\Models\CoreServiceGroupItem;
use App\Models\ClientConServiceValue;
use App\Models\CampaignChannelParameter;
use App\Models\CampaignChannelDistribution;
use App\Models\CoreCampaignStatus;
use App\Models\CampaignServiceGroupOrder;
use ZipArchive;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Auth;

class PlanningController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
       $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clientName = Client::all();
        return view('pages.planning.list', ['tabIndex' =>1,'clientName' =>$clientName]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function add(Request  $request)
    {
        $clientName = $request->clientName;
        $campaignName = ($request->campaignName == NULL) ? "" : $request->campaignName;
        
        $campaignID = 1;

        if (Campaign::where('name', $campaignName)->first()) {
            return response()->json(array('status'=>'error'));
        }

        $onlineCheck = $request->onlineCheck;
        $printCheck = $request->printCheck;
        $plakatCheck = $request->plakatCheck;
        $kinoCheck = $request->kinoCheck;
        $radioCheck = $request->radioCheck;
        $tvCheck = $request->tvCheck;
        $ambientCheck = $request->ambientCheck;
        $dt_online_start = $request->dt_online_start;
        $dt_online_end = $request->dt_online_end;

        $optionCheck = $request->optionCheck;

        $dt_print_start = $request->dt_print_start;
        $dt_print_end = $request->dt_print_end;

        $dt_plakat_start = $request->dt_plakat_start;
        $dt_plakat_end = $request->dt_plakat_end;

        $dt_kino_start = $request->dt_kino_start;
        $dt_kino_end = $request->dt_kino_end;

        $dt_radio_start = $request->dt_radio_start;
        $dt_radio_end = $request->dt_radio_end;

        $dt_tv_start = $request->dt_tv_start;
        $dt_tv_end = $request->dt_tv_end;

        $dt_ambient_start = $request->dt_ambient_start;
        $dt_ambient_end = $request->dt_ambient_end;

        $campaign = new Campaign;
        $campaign->name = $campaignName;
        $campaign->clientID = Client::where('name',$clientName)->first()->ID;
        $campaign->statusID = 1;
        $campaign->save();
        $campaignID = $campaign->ID;

	    if ($onlineCheck == "true") {
            $channel = new CampaignChannel;
            $channel->campaignID = Campaign::where('name', $campaignName)->first()->ID;
            $channel->name = 'online';
            $channel->startDate = date("Y-m-d", strtotime($dt_online_start));
            $channel->endDate = date("Y-m-d", strtotime($dt_online_end));
            if($optionCheck == "false")
                $channel->hasExtraWeek = 0;
            else
                $channel->hasExtraWeek = 1;

            $channel->save();
        }

        if ($printCheck == "true") {
            $channel = new CampaignChannel;
            $channel->campaignID = Campaign::where('name', $campaignName)->first()->ID;
            $channel->name = 'print';
            $channel->startDate = date("Y-m-d", strtotime($dt_print_start));
            $channel->endDate = date("Y-m-d", strtotime($dt_print_end));
            $channel->save();
        }

        if ($plakatCheck == "true") {
            $channel = new CampaignChannel;            
            $channel->campaignID = Campaign::where('name', $campaignName)->first()->ID;
            $channel->name = 'plakat';
            $channel->startDate = date("Y-m-d", strtotime($dt_plakat_start));
            $channel->endDate = date("Y-m-d", strtotime($dt_plakat_end));
            $channel->save();  
        }

        
        if ($radioCheck == "true") {
            $channel = new CampaignChannel;            
            $channel->campaignID = Campaign::where('name', $campaignName)->first()->ID;
            $channel->name = 'radio';
            $channel->startDate = date("Y-m-d", strtotime($dt_radio_start));
            $channel->endDate = date("Y-m-d", strtotime($dt_radio_end));
            $channel->save(); 
        }
        
        if ($tvCheck == "true") {
            $channel = new CampaignChannel;            
            $channel->campaignID = Campaign::where('name', $campaignName)->first()->ID;
            $channel->name = 'tv';
            $channel->startDate = date("Y-m-d", strtotime($dt_tv_start));
            $channel->endDate = date("Y-m-d", strtotime($dt_tv_end));
            $channel->save(); 
        }
        
        if ($kinoCheck == "true") {
            $channel = new CampaignChannel;            
            $channel->campaignID = Campaign::where('name', $campaignName)->first()->ID;
            $channel->name = 'kino';
            $channel->startDate = date("Y-m-d", strtotime($dt_kino_start));
            $channel->endDate = date("Y-m-d", strtotime($dt_kino_end));
            $channel->save(); 
        }

        if ($ambientCheck == "true") {
            $channel = new CampaignChannel;
            $channel->campaignID = Campaign::where('name', $campaignName)->first()->ID;
            $channel->name = 'ambient';
            $channel->startDate = date("Y-m-d", strtotime($dt_ambient_start));
            $channel->endDate = date("Y-m-d", strtotime($dt_ambient_end));
            $channel->save();
        }

        $data = array( 'status' => 'success!', 'id' => $campaignID );
        
        return response()->json($data);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCampaign(Request $request){
        $campaignID = $request->campaignID;
        $campaign = Campaign::where('ID',$campaignID)->first();
        $campaignChannels = CampaignChannel::all()->where('campaignID',$campaignID);
        $campaignName = $campaign->name;
        $clientID = $campaign->clientID;
        $clientName = Client::all() ->where('ID',$clientID)->first()->name;
        $campaignStatus = $campaign->statusID;           
        $hasExtraWeek = 0;
        
        $channelName = array();
        $startDate = array();
        $endDate = array();    
 
        foreach ($campaignChannels as $campaignChannel) {
            $channelName[] = $campaignChannel->name;
            $hasExtraWeek += $campaignChannel->hasExtraWeek;
            $startDate[] = date("d.m.Y", strtotime($campaignChannel->startDate));                  
            $endDate[] = date("d.m.Y", strtotime($campaignChannel->endDate)); 
        }

         $data = array(
            'campaignID' => $campaignID,
            'clientName' => $clientName,
            'clientID' => $clientID,
            'campaignName' => $campaignName,
            'campaignStatus' =>$campaignStatus,
            'channelName' => $channelName,
            'hasExtraWeek' => $hasExtraWeek,
            'startDate' => $startDate,
            'endDate' => $endDate,
        );

        return response()->json($data);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function getChannel(Request $request){
        $campaignID = $request->selectedID;
        $campaign = Campaign::where('ID',$campaignID)->first();
        $campaignChannels = CampaignChannel::all()->where('campaignID',$campaignID);
        $version = array();
        $versionTime = array();
        $versionTimeHMS = array();
        $versionUser = array();
        $channelName = array();
        $mediaTotal = array();
        $nnCHFTotal = array();

        foreach ($campaignChannels as $campaignChannel) {
            $name = $campaignChannel->name;
            if($name == 'tv'){
                $channelName[] = 'TV';
            }
            else{
                $channelName[] = $name;
            }
            $channelversion = CampaignChannelVersion::all()->where('channelID',$campaignChannel->ID)->first();
            if (!$channelversion) {
                $version[] = "V 1.0" ;
                $versionTime[] = date("d.m.Y", time());
                $versionTimeHMS[] = date("H:i", time());
                $versionUser[] = Auth::user()->initials;
                $newVersion = new CampaignChannelVersion;
                $newVersion->versionNumber = "V 1.0";
                $newVersion->channelID = $campaignChannel->ID;
                $newVersion->userID =  Auth::user()->ID;
                $newVersion->changedDateTime = date("Y-m-d H:i", time());
                $newVersion->save();
            } else {
                $version[] = $channelversion->versionNumber;
                $versionTime[] = date("d.m.Y",strtotime($channelversion->changedDateTime));
                $versionTimeHMS[] = date("H:i",strtotime($channelversion->changedDateTime));
                $versionUser[] = $channelversion->corerightuser->initials;
            }

            $query = "select SUM(t1.adPressureValue) as subTotal, SUM(t1.nnCHF) as nnCHFTotal
                        from campaign_channels_media t1
                        left join campaign_channels_media_con_categories t2
                        on t1.categoryID = t2.ID
                        where t2.channelID = ?";

            $media = DB::select($query,[$campaignChannel->ID]);      
            
            $subTotal = (count($media) > 0) ? $media[0]->subTotal : "0.00";
            $nnCHF = (count($media) > 0) ? $media[0]->nnCHFTotal : "0.00";
            $mediaTotal[] = floatval($subTotal);
            $nnCHFTotal[] = floatval($nnCHF);
        }

        $data = array(
            'name' => $channelName,
            'version' => $version,
            'versionTime' => $versionTime,
            'versionTimeHMS' => $versionTimeHMS,
            'versionUser' => $versionUser,
            'mediaTotal' => $mediaTotal,
            'nnCHFTotal' => $nnCHFTotal
        );
        
        return response()->json($data);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request  $request){
        $clientName = $request->clientName;
        $campaignID = $request->campaignID;
        $campaignName = $request->campaignName;
        $onlineCheck = $request->onlineCheck;
        $printCheck = $request->printCheck;
        $plakatCheck = $request->plakatCheck;
        $kinoCheck = $request->kinoCheck;
        $radioCheck = $request->radioCheck;
        $tvCheck = $request->tvCheck;
        $ambientCheck = $request->ambientCheck;

        $dt_online_start = $request->dt_online_start;
        $dt_online_end = $request->dt_online_end;

        $optionCheck = $request->optionCheck;

        $dt_print_start = $request->dt_print_start;
        $dt_print_end = $request->dt_print_end;

        $dt_plakat_start = $request->dt_plakat_start;
        $dt_plakat_end = $request->dt_plakat_end;

        $dt_kino_start = $request->dt_kino_start;
        $dt_kino_end = $request->dt_kino_end;

        $dt_radio_start = $request->dt_radio_start;
        $dt_radio_end = $request->dt_radio_end;

        $dt_tv_start = $request->dt_tv_start;
        $dt_tv_end = $request->dt_tv_end;

        $dt_ambient_start = $request->dt_ambient_start;
        $dt_ambient_end = $request->dt_ambient_end;


        if ($campaignName && $clientName) {
            $campaign = Campaign::find($campaignID);
            $campaign->name = $campaignName;
            $campaign->clientID = Client::where('name',$clientName)->first()->ID;       
            $campaign->save(); 
        } 

        $isChecked = CampaignChannel::where('campaignID',$campaignID)->where('name' , 'online')->count();
        
        if ($onlineCheck == 'true' && $isChecked == 0) {

            $this->channelClear($campaignID,$dt_online_start,$dt_online_end, $optionCheck, 'online');

        } else if ($onlineCheck == 'true'  && $isChecked == 1) {
            $this->channelUpdate($campaignID,$dt_online_start,$dt_online_end, $optionCheck, 'online');

        } else if ($onlineCheck == 'false' && $isChecked == 1) {
            $this->channelDelete($campaignID, 'online');
        }     
        
        $isChecked = CampaignChannel::where('campaignID',$campaignID)->where('name' , 'print')->count();
        
        if ($printCheck == 'true' && $isChecked == 0) {

            $this->channelClear($campaignID,$dt_print_start,$dt_print_end, "false", 'print');

        } else if ($printCheck == 'true' && $isChecked == 1) {

            $this->channelUpdate($campaignID,$dt_print_start,$dt_print_end, "false", 'print');

        } else if ($printCheck == 'false' && $isChecked == 1) {
            $this->channelDelete($campaignID, 'print');
        }
        
        $isChecked = CampaignChannel::where('campaignID',$campaignID)->where('name' , 'plakat')->count();
        
        if ($plakatCheck == 'true' && $isChecked == 0) {

            $this->channelClear($campaignID,$dt_plakat_start,$dt_plakat_end, "false", 'plakat');

        } else if ($plakatCheck == 'true'  && $isChecked == 1) {
            $this->channelUpdate($campaignID,$dt_plakat_start,$dt_plakat_end, "false", 'plakat');
        } else if ($plakatCheck =='false' && $isChecked == 1) {
            $this->channelDelete($campaignID, 'plakat');
        }

        //////
        $isChecked = CampaignChannel::where('campaignID',$campaignID)->where('name' , 'tv')->count();
        
        if ($tvCheck == 'true' && $isChecked == 0) {
            $this->channelClear($campaignID,$dt_tv_start,$dt_tv_end, "false", 'tv');
        }  else if ($tvCheck == 'true'  && $isChecked == 1) {
            $this->channelUpdate($campaignID,$dt_tv_start,$dt_tv_end, "false", 'tv');
        } else if ($tvCheck =='false' && $isChecked == 1) {
            $this->channelDelete($campaignID, 'tv');
        }
        
        $isChecked = CampaignChannel::where('campaignID',$campaignID)->where('name' , 'kino')->count();
        
        if ($kinoCheck == 'true' && $isChecked == 0) {
            $this->channelClear($campaignID,$dt_kino_start,$dt_kino_end, "false", 'kino');
        }  else if ($kinoCheck == 'true'  && $isChecked == 1) {
            $this->channelUpdate($campaignID,$dt_kino_start,$dt_kino_end, "false", 'kino');
        } else if ($kinoCheck =='false' && $isChecked == 1) {
            $this->channelDelete($campaignID, 'kino');
        }

        $isChecked = CampaignChannel::where('campaignID',$campaignID)->where('name' , 'radio')->count();

        if ($radioCheck == 'true' && $isChecked == 0) {
            $this->channelClear($campaignID,$dt_radio_start,$dt_radio_end, "false", 'radio');
        }  else if ($radioCheck == 'true'  && $isChecked == 1) {
            $this->channelUpdate($campaignID,$dt_radio_start,$dt_radio_end, "false", 'radio');
        } else if ($radioCheck =='false' && $isChecked == 1) {
            $this->channelDelete($campaignID, 'radio');
        }

        $isChecked = CampaignChannel::where('campaignID',$campaignID)->where('name' , 'ambient')->count();

        if ($ambientCheck == 'true' && $isChecked == 0) {
            $this->channelClear($campaignID,$dt_ambient_start,$dt_ambient_end, "false", 'ambient');
        }  else if ($ambientCheck == 'true'  && $isChecked == 1) {
            $this->channelUpdate($campaignID,$dt_ambient_start,$dt_ambient_end, "false", 'ambient');
        } else if ($ambientCheck =='false' && $isChecked == 1) {
            $this->channelDelete($campaignID, 'ambient');
        }

        $data = array( 'status' => 'success!','id' => $campaignID );
        
        return response()->json($data);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function overview(Request $request)
    {
        $campaignID = $request->id;

        if (!empty($campaignID)) 
        {
            
            $campaign = Campaign::where('ID', $campaignID)->first();

            //session(['campaignName' => $campaign->name]);

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
                //$groupNm = $this->indexToGroupName($serviceCosts_channel, $index);
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
			$campigncomments = $campaign->comments;

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
//                    $mediaCosts[$key]["percentage"] = number_format(floatval($mediaCost["subTotal"]) / floatval($mediaTotal) * 100, 2);
                    if($nnCHFTotal != 0)
                        $mediaCosts[$key]["percentage"] = number_format(floatval($mediaCost["nnCHF"]) / floatval($total) * 100, 2);
                    else
                        $mediaCosts[$key]["percentage"] = 0;
                }

                //$mediaCosts[$key]["subTotal"] = number_format($mediaCost["subTotal"], 2,".","'");
                $mediaCosts[$key]["subTotal"] = number_format($mediaCost["nnCHF"], 2,".","'");
            }

            $mediaData = array(
                    //"total" => number_format(floatval($mediaTotal), 2,".","'"),
                    "total" => number_format(floatval($nnCHFTotal), 2,".","'"),
                    "mediaCosts" => $mediaCosts
            );


//            $total = floatval($mediaTotal) + floatval($serviceTotal);

            
            if($total == 0){
                $cost_percentage = "0.00";
            }
            else{
                $cost_percentage = number_format($serviceTotal * 100 / $total , 2);
            }

            $vat = config('constant.VAT');
            $totalMWST = ($total * (100 + $vat)) / 100;
            
            if ($total == 0) {
                $serviceData["percentage"] = "0.00";
                $mediaData["percentage"] = "0.00";
            } else {
                $serviceData["percentage"] = number_format(floatval($serviceTotal) / floatval($total) * 100, 2);
//                $mediaData["percentage"] = number_format(floatval($mediaTotal) / floatval($total) * 100, 2);
                $mediaData["percentage"] = number_format(floatval($nnCHFTotal) / floatval($total) * 100, 2);
            }

            $campaignExport = CampaignExport::where('campaignID', $campaignID)->orderBy('version', 'desc')->get();
            
            $data = array(
                'campaignID' => $campaignID,
                'campaignExport' => $campaignExport,
                'onlineChannel' => $onlineChannelID,
                'customerName' => $customerName,
                'campaignName' => $campaignName,
                'channelNames' => $channels,
                'channelsVersions' => $versions,
                'traffickosten' => $adP,
                'honorar' => $NNInChf,
                'totalStartdate' => date("d.m.Y",$totalStartTime),
                'totalEnddate' => date("d.m.Y",$totalEndTime),
                'planningStatus' => $status,
                'statuses' => $statuses,
				'comments' => $campigncomments,
                'costData' => array(
                        'vat' => $vat,
                        'serviceData' => $serviceData,
                        'mediaData' => $mediaData,
                        'total' => number_format($total, 2,".","'"),
                        'cost_percentage' => $cost_percentage,
                        'totalMWST' => number_format($totalMWST,2,".","'")
                    )
            );


            // Render planning view
            return view('pages.planning.overview',['tabIndex' => 1,'data' => $data
                , 'campaignID' => $campaignID,'channels' => $channels,'activetab' => "overview"]);
        }
        
        return redirect('planning');
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

    /* */
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


    protected function getFixedRates($customerID = 0)
    {
        $query = "select * from clients_con_services_values where clientID = ? and (conServiceID = 22 or conServiceID = 21)";
        // 22 = Zusatzhonorar, 21 = Maintenance

        $result = DB::select($query, [$customerID]);

        $fixedServices = array();

        foreach($result as $item) {
            array_push($fixedServices, $item);
        }

        return $fixedServices;
    }

     /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function params(Request $request)
    {
        $campaignID = $request->id;
        $active_channel = $request->channel;

        $campaign = Campaign::where('ID', $campaignID)->first();
        $customerID = -1;
        $campaignName = '';
        
        if ($campaign) {
            $customerID = $campaign->clientID;
            $campaignName = $campaign->name;
        }

        $fixedValues = $this->getFixedRates($customerID);

        $channels = CampaignChannel::where('campaignID', $campaignID)->get()->toArray();

        $channel = CampaignChannel::where('name',$active_channel)->where('campaignID', $campaignID)->first();
        $channelID = -1;

        if ($channel) {
            $channelID = $channel->ID;
        } else {
            return redirect('planning/overview?id='.$campaignID);
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

        $NNInChf = $this->calculateNNInChfSum($campaignID, $active_channel);
        $AdP = $this->calculateadPSum($campaignID, $active_channel);

        $NNInChf = number_format($NNInChf, 2,".","'");
        $AdP = number_format($AdP, 2,".","'");


        return view('pages.planning.params', [
                        'tabIndex' => 1, 
                        'activetab' => $active_channel,
                        'campaignID' => $campaignID,
                        'campaignName'=> $campaignName,
                        'channelID' => $channelID,
                        'nnInChf' => $NNInChf,
                        'adP' => $AdP,
                        'parameters' => $parameters, 
                        'rates_groupwithname' => $selectRates_GroupWithName,
                        'rates' => $selectRates,
                        'channels' => $channels]);
    }
    
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function updateParams(Request $request)
    {

        $campaignID = $request->campaignID;
        $channelID = $request->channelID;
        $newDatas = $request->newDatas;
        $orgDatas = $request->orgDatas;
        
        $campaign = Campaign::where('ID', $campaignID)->first();
        $customerID = -1;

        if ($campaign) {
            $customerID = $campaign->clientID;
        }

        // plus version number
        $channelversion = CampaignChannelVersion::all()->where('channelID',$channelID)->first();
        // var_dump(date("d-m-Y", time()));exit();
        if (!$channelversion) {
            $newVersion = new CampaignChannelVersion;
            $newVersion->versionNumber = "V 1.0";
            $newVersion->channelID = $channelID;
            $newVersion->userID = auth::user()->ID;
            $newVersion->changedDateTime = date("Y-m-d H:i", time());
            $newVersion->save();
        } else {
            $str = array();
            $str = explode('.', $channelversion->versionNumber);
            $channelversion->versionNumber = $str[0].".".($str[1]+1);
            $channelversion->userID = auth::user()->ID;
            $channelversion->changedDateTime = date("Y-m-d H:i", time());
            $channelversion->save();
        }

        $respNews = array();
        $respOrgs = array();

        if (!empty($newDatas)) {
            foreach ($newDatas as $newData) {
                $groupItem = array();
                $groupID = DB::table('core_service_groups')->insertGetId(['channelID' => $channelID, 'name' => $newData['name'], 'value' => 'HOURLY_RATE']);
                $sortID = DB::table('campaign_service_groups_orders')->insertGetId(['channelID' => $channelID, 'groupID' => $groupID, 'sortOrder' => $newData['sortOrder']]);
                $groupItem["groupID"] = $groupID;
                $groupItem["childs"] = array();
                
                if (isset($orgDatas)) {
                    foreach ($newData["elems"] as $elemData) {
                        $clientServiceItemID = $elemData['csID'];
                        
                        if ($clientServiceItemID == "-1") {
                            $clientServiceItem = ClientConServiceValue::where('clientID',$customerID)->where('conServiceID', $elemData['conID'])->first();

                            if ($clientServiceItem) {
                                $clientServiceItemID = $clientServiceItem->ID;
                            } else {
                                $clientServiceItemID = DB::table('clients_con_services_values')->insertGetId(['clientID' => $customerID, 'conServiceID' => $elemData['conID'], 'value' => $elemData['csVal']]);
                            }
                        }

                        $serviceItemID = DB::table('core_service_groups_items')->insertGetId(['groupID' => $groupID, 'channelID'=>$channelID, 'name' => $elemData['name'], 'value' => 'HOURLY_RATE']);
                        
                        $paramID = DB::table('campaign_channels_parameters')->insertGetId(['channelID' => $channelID, 'serviceItemID' => $serviceItemID, 'clientServiceItemID' => $clientServiceItemID, 'name' => $elemData['name'], 'value' => $elemData['value'], 'calcValue' => $elemData['calcValue'], 'isFlatrate' => $elemData['isFlatrate']]);
                        
                        $costItem = array(
                                'paramID' => $paramID,
                                'serviceID' => $serviceItemID,
                                'conID' => $clientServiceItemID
                            );

                        array_push($groupItem['childs'], $costItem);
                    }
                }
                array_push($respNews, $groupItem);
            }
        }

        if (!empty($orgDatas)) {
            
            foreach ($orgDatas as$orgData) {
                $groupID = $orgData['ID'];
                $sortCnt = CampaignServiceGroupOrder::where('channelID',$channelID)->where('groupID',$groupID)->count();
                
                if ($sortCnt == 0) {
                    $sortID = DB::table('campaign_service_groups_orders')->insertGetId(['channelID' => $channelID, 'groupID' => $groupID, 'sortOrder' => $orgData['sortOrder']]);
                }
                else {
                    $sortID = DB::table('campaign_service_groups_orders')->where('channelID', $channelID)->where('groupID',$groupID)->update(['sortOrder' => $orgData['sortOrder']]);
                }

                if (isset($orgData["elems"])) { 
                    foreach ($orgData["elems"] as  $ksor=>$elemData) {
//                        dd($elemData);
                        if(!isset($elemData['csID'])){
                            $elemData['csID'] = "-1";
                            $elemData['value'] = 0;
                        }
                        if (isset($elemData["ID"]) && !empty($elemData["ID"])) {
                            $paramID = DB::table('campaign_channels_parameters')
                            ->where('ID', $elemData["ID"])
                            ->update(['clientServiceItemID' => $elemData['csID'], 'value' => $elemData['value'], 'calcValue' => $elemData['calcValue'], 'isFlatrate' => $elemData['isFlatrate']]);

                            DB::table('core_service_groups_items')->where('ID',  $elemData["itemID"])->update(['sortOrder' => $elemData['sortOrder']]);
                        } else {
                            $serviceItemID = 0;

                           
                            if (isset($elemData["itemID"]) && !empty($elemData["itemID"])) {
                                $serviceItemID = $elemData["itemID"];
                                DB::table('core_service_groups_items')->where('ID',  $elemData["itemID"])->update(['sortOrder' => $elemData['sortOrder']]);
                            } else {
                                $serviceItemID = DB::table('core_service_groups_items')->insertGetId(['groupID' => $groupID, 'channelID' => $channelID, 'name' => $elemData['name'],'sortOrder' => $ksor+1, 'value' => 'HOURLY_RATE']);
                            }

                            $clientServiceItemID = "-1";
                            if(isset($elemData['csID'])) {

                                $clientServiceItemID = $elemData['csID'];

                                if ($clientServiceItemID == "-1") {

                                    if($elemData['name'] == 'Maintenance'){
                                        $elemData['conID'] = 21;
                                    }
                                    if($elemData['name'] == 'Zusatzhonorar'){
                                        $elemData['conID'] = 22;
                                    }

                                    $clientServiceItem = ClientConServiceValue::where('clientID', $customerID)->where('conServiceID', $elemData['conID'])->first();

                                    if ($clientServiceItem) {
                                        $clientServiceItemID = $clientServiceItem->ID;
                                    } else {
                                        $clientServiceItemID = DB::table('clients_con_services_values')->insertGetId(['clientID' => $customerID, 'conServiceID' => $elemData['conID'], 'value' => $elemData['csVal']]);
                                    }
                                }
                            }

                            if(!isset($elemData['value'])) $elemData['value'] = 0;

                            $paramID = DB::table('campaign_channels_parameters')->insertGetId(['channelID' => $channelID, 'serviceItemID' => $serviceItemID, 'clientServiceItemID' => $clientServiceItemID, 'name' => $elemData['name'], 'value' => $elemData['value'], 'calcValue' => $elemData['calcValue'], 'isFlatrate' => $elemData['isFlatrate']]);
                            
                            $costItem = array(
                                'paramID' => $paramID,
                                'serviceID' => $serviceItemID,
                                'groupID' => $groupID,
                                'conID' => $clientServiceItemID
                            );

                            array_push($respOrgs, $costItem);
                        }
                    }
                }
            }
        }

        $resp = array(
                'status' => 'success',
                'msg' => 'Successfully updated',
                'data' => array(
                        'newDatas' => $respNews,
                        'orgDatas' => $respOrgs
                    )
            );

        return response()->json($resp);
    }
    
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteParams(Request $request)
    {
        $channelID = $request->channelID;
        $groupID = $request->groupID;

        DB::table('core_service_groups')->where('ID', '=', $groupID)->where('channelID','=',$channelID)->delete();

        DB::table('core_service_groups_items')->where('groupID', '=', $groupID)->where('channelID','=',$channelID)->delete();

        DB::table('campaign_channels_parameters')->where('ID', '=', $groupID)->where('channelID','=',$channelID)->delete();

        $resp = array(
                'status' => 'success',
                'msg' => 'Successfully deleted'
            );
        return response()->json($resp);
    }

    public function deleteGroupItem(Request $request)
    {
        $serviceGroupItemId = $request->serviceGroupItemId;

        DB::table('core_service_groups_items')->where('ID', '=', $serviceGroupItemId)->delete();

        $resp = array(
            'status' => 'success',
            'msg' => 'Successfully deleted'
        );
        return response()->json($resp);
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function changeOption(Request $request){
        $campaign = Campaign::where('ID',$request->currentCampaingID)->first();
        $campaign->statusID = ($request->optionValue == "true")? 2 : 1;
        $msg = ($campaign->save())? "Change Successed!" : "Change Failed!";

        $resp = array(
            'status' => 'OK',
            'msg' => $msg
            );

        return response()->json($resp);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function channelBtnClick(Request $request){

        if ($request->channel_value == "true") {
            $channel = new CampaignChannel;
            $channel->campaignID = $request->current_campaing_id;
            $channel->name = $request->channel_name;
            $channel->hasExtraWeek = 0;
            $msg = ($channel->save())? "Active Successed!" : "Active Failed!";
        } else {
            $channel = CampaignChannel::where('campaignID',$request->current_campaing_id)->where('name',$request->channel_name)->first();
            $msg = ($channel->delete())? "Inactive Successed!" : "Inactive Failed!";            
        }

        $resp = array(
            'status' => 'OK',
            'msg' => $msg
            );

        return response()->json($resp);
    }    

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function serverProcessing(Request $request){
        $sql = 'SELECT
                    campaigns.ID,
                    campaigns.`name` AS campaignName,
                    clients.`name` AS clientName,
                    campaigns.statusID
                FROM
                    campaigns
                RIGHT JOIN clients ON clients.ID = campaigns.clientID
                RIGHT JOIN campaign_channels ON campaign_channels.campaignID = campaigns.ID';

        $sOrder = "ORDER BY campaigns.ID ASC";



        $sLimit = "";
        
        if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' ) {
            $sLimit = "LIMIT ".intval( $_GET['iDisplayStart'] ).", ".
                intval( $_GET['iDisplayLength'] );
        }
        
        $sWhere = "WHERE campaigns.`name` IS NOT NULL ";
        
        if ( isset($_GET['sSearch']) && $_GET['sSearch'] != "" ) {
            $sWhere .= ' AND (clients.`name` LIKE "%'.$_GET['sSearch'].'%" OR campaigns.`name` LIKE "%'.$_GET['sSearch'].'%")';
        }
        
        if ( $_GET['sSearch_1'] != '' ) {
            $statusArr = array("Alle Planungen","Inaktiv","Aktiv","Live","Abgeschlossen",);
            $statusID = array_search($_GET['sSearch_1'],$statusArr);
            switch ($statusID){
                case 4:
                    if ( $sWhere == "" ) $sWhere = "WHERE "; else $sWhere .= " AND ";
                    $sWhere .= "(campaigns.statusID = 2 AND DATE_ADD(campaign_channels.endDate, INTERVAL 30 DAY) < '".date("Y-m-d")."')";
                    break;
                case 1:
                case 2:
                case 3:
                    if ( $sWhere == "" ) $sWhere = "WHERE "; else $sWhere .= " AND ";
                    $sWhere .= "(campaigns.statusID = ".$statusID." )";
                    break;
                default:
            }


        }
        
        if ( $_GET['sSearch_2'] != '' ) {         
            if ( $sWhere == "" ) $sWhere = "WHERE "; else $sWhere .= " AND ";
            $sWhere .= "clients.`name` = '".$_GET['sSearch_2']."' ";
        }        
        $groupBy = " GROUP BY ID ";
        $sQuery = "$sql
            $sWhere
            $groupBy
            $sOrder
            $sLimit
            ";
//        die($sQuery);
        $enteries = DB::select($sQuery, array());   

        $data = array();
        
        foreach ($enteries as $campaign) {
            $campaignID = $campaign->ID;
            $campaignName = $campaign->campaignName;
            $clientName = $campaign->clientName;
            $campaignStatus = $campaign->statusID;
            $campaignChannels = CampaignChannel::all()->where('campaignID',$campaignID);
            $startDate = date("2100-01-01");
            $endDate = date("1970-01-01");
            
            $version = array();
            $versionTime = array();
            $channelName = array();

            foreach ($campaignChannels as $campaignChannel) {
                $name = $campaignChannel->name;
                $channelName[] = $name;
                if ($name =='online' || $name == 'print' || $name == 'plakat'
                    || $name == 'radio' || $name == 'tv' || $name == 'kino' || $name == 'ambient'){
                    if ($startDate > date($campaignChannel->startDate)) {
                        $startDate = date($campaignChannel->startDate);                  
                    }

                    if ($name == 'online' && $campaignChannel->hasExtraWeek == 1) {
                        $calendarDate = date_create($campaignChannel->endDate);
                        date_add($calendarDate,date_interval_create_from_date_string("7 days"));

                        if ($endDate < date_format($calendarDate,"Y-m-d")) {
                            $endDate = date_format($calendarDate,"Y-m-d");
                        }
                    }

                    if ($endDate < date($campaignChannel->endDate)) {
                        $endDate = date($campaignChannel->endDate);                  
                    }

                    $channelversion = CampaignChannelVersion::all()->where('channelID',$campaignChannel->ID)->first();
                    // var_dump(date("d-m-Y", time()));exit();
                    if (!$channelversion) {
                        $newVersion = new CampaignChannelVersion;
                        $version[$name] = "V 1.0";
                        $versionTime[$name] = date("Y-m-d H:i", time());
                        $newVersion->versionNumber = $version[$name];
                        $newVersion->channelID = $campaignChannel->ID;
                        $newVersion->userID = auth::user()->ID;
                        $newVersion->changedDateTime = $versionTime[$name];
                        $newVersion->save();
                    } else {
                        $version[$name] = $channelversion->versionNumber;
                        $versionTime[$name] = $channelversion->changedDateTime;
                    }
                }
            }

            $showTime = date("d.m.",strtotime($startDate))." - ".date("d.m.y",strtotime($endDate));
            $startDay = getdate(strtotime($startDate));
            $startWeek = ceil($startDay["yday"] / 7)+1;            
            $endDay = getdate(strtotime($endDate));
            $endWeek = floor($endDay["yday"] / 7)+1;              
            $showTime .= "/ KW ".$startWeek." - ".$endWeek;
            
            $data[] = array(
                'campaignID' => $campaignID,
                'clientName' => $clientName,
                'campaignName' => $campaignName,
                'campaignStatus' =>$campaignStatus,
                'channelName' => $channelName,
                'startDate' => $startDate,
                'endDate' => $endDate,
                'version' => $version,
                'versionTime' =>$versionTime,
                'showTime' => $showTime,
                'urlID' => $campaignID,
            );  
        }

        $results =  array(
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData"=> $data
        );
        
        return response()->json($results);
    }

    public function calculateNNInChfSum($campaignID, $active_channel){


        $currentChannel = CampaignChannel::where('campaignID' ,$campaignID)->where('name',$active_channel)->first();

        if ($currentChannel) {
            $channelID = $currentChannel->ID;
        } else {
            return redirect('planning/overview?id='.$campaignID);
        }

        $currentCategory = CampaignChannelMediaConCategory::where('channelID',$channelID)->get();
        $currentCategories = array();

        foreach ($currentCategory as $ctg) {
            $currentCategories[$ctg->categoryID] = CoreCampaignCategory::where('ID',$ctg->categoryID)->where('channel_name',$active_channel)->first();
        }

        $categories = CampaignChannel::where('campaignID',$campaignID)->where('name',$active_channel)->first()->campaignChannelmediaconcategory;

        $sum = 0;

        foreach ($categories as $category) {

            $medias = $category->campaignchannelmedia;

            foreach ($medias as $media) {
                $sum += $media ->nnCHF;
            }

        }

        return $sum;
    }

    public function calculateadPSum($campaignID, $active_channel){


        $currentChannel = CampaignChannel::where('campaignID' ,$campaignID)->where('name',$active_channel)->first();

        if ($currentChannel) {
            $channelID = $currentChannel->ID;
        } else {
            return redirect('planning/overview?id='.$campaignID);
        }

        $currentCategory = CampaignChannelMediaConCategory::where('channelID',$channelID)->get();
        $currentCategories = array();

        foreach ($currentCategory as $ctg) {
            $currentCategories[$ctg->categoryID] = CoreCampaignCategory::where('ID',$ctg->categoryID)->where('channel_name',$active_channel)->first();
        }

        $categories = CampaignChannel::where('campaignID',$campaignID)->where('name',$active_channel)->first()->campaignChannelmediaconcategory;

        $sum = 0;

        foreach ($categories as $category) {

            $medias = $category->campaignchannelmedia;

            foreach ($medias as $media) {
                $sum += $media ->adPressureValue;
            }

        }

     return $sum;
    }
	
	public function SaveComments(Request $request)
	{
        $channelID = $request->id;
		$comments = $request->comments;

        $sortID = DB::table('campaigns')->where('ID', $channelID)->update(['comments' => $comments]);
		 
        $data = array( 'status' => 'success!', 'id' => $channelID );
        
        return redirect('/planning/overview?id=' . $channelID);
	}

    public function campaignExport(Request $request)
    {
        $campaignExport = new CampaignExport;
        $campaignExport->campaignID = (int)$request->ID;
        $isNew = 0;


        $campaign = Campaign::where('ID', $request->ID)->first();
        $campaignName = $campaign->name;
        $file_name = 'F'.$request->ID.'_'.str_slug($campaignName).'_'.$this->str_clean($request->version).'_'.$request->vDate.'.pdf'.'.zip';

        $isExit = CampaignExport::where('version', $request->version)
            ->where('date',$request->vDate)
            ->where('campaignID',$request->ID)
            ->first();
        if(empty($isExit)){
            $campaignExport->version = $request->version;
            $campaignExport->date = $request->vDate;
            $campaignExport->export_file = $file_name;
            $campaignExport->save();
            $isNew = 1;
        }


        $data = array(
            'version' => $request->version,
            'date' => $request->vDate,
            'file_name' =>$file_name,
            'isNew'=>$isNew
        );

        return response()->json($data);
    }

    public function download($file)
    {
        $file_path = public_path() . '/uploads/pdf/' . $file;
//        die($file_path);

        if (file_exists($file_path)) {

            header('Content-Type: application/pdf');
            header('Content-disposition: attachment;filename=' . $file);
            readfile($file_path);

        } else {
            return abort(404);
        }
    }

    /**
     * @param $interval
     * @param $datefrom
     * @param $dateto
     * @param bool $using_timestamps
     * @return false|float|int|string
     * $interval can be:
        yyyy - Number of full years
        q    - Number of full quarters
        m    - Number of full months
        y    - Difference between day numbers
        (eg 1st Jan 2004 is "1", the first day. 2nd Feb 2003 is "33". The datediff is "-32".)
        d    - Number of full days
        w    - Number of full weekdays
        ww   - Number of full weeks
        h    - Number of full hours
        n    - Number of full minutes
        s    - Number of full seconds (default)
     */
    private function datediff($interval, $datefrom, $dateto, $using_timestamps = false)
    {

        if (!$using_timestamps) {
            $datefrom = strtotime($datefrom, 0);
            $dateto   = strtotime($dateto, 0);
        }

        $difference        = $dateto - $datefrom; // Difference in seconds
        $months_difference = 0;

        switch ($interval) {
            case 'yyyy': // Number of full years
                $years_difference = floor($difference / 31536000);
                if (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom), date("j", $datefrom), date("Y", $datefrom)+$years_difference) > $dateto) {
                    $years_difference--;
                }

                if (mktime(date("H", $dateto), date("i", $dateto), date("s", $dateto), date("n", $dateto), date("j", $dateto), date("Y", $dateto)-($years_difference+1)) > $datefrom) {
                    $years_difference++;
                }

                $datediff = $years_difference;
                break;

            case "q": // Number of full quarters
                $quarters_difference = floor($difference / 8035200);

                while (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom)+($quarters_difference*3), date("j", $dateto), date("Y", $datefrom)) < $dateto) {
                    $months_difference++;
                }

                $quarters_difference--;
                $datediff = $quarters_difference;
                break;

            case "m": // Number of full months
                $months_difference = floor($difference / 2678400);

                while (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom)+($months_difference), date("j", $dateto), date("Y", $datefrom)) < $dateto) {
                    $months_difference++;
                }

                $months_difference--;

                $datediff = $months_difference;
                break;

            case 'y': // Difference between day numbers
                $datediff = date("z", $dateto) - date("z", $datefrom);
                break;

            case "d": // Number of full days
                $datediff = floor($difference / 86400);
                break;

            case "w": // Number of full weekdays
                $days_difference  = floor($difference / 86400);
                $weeks_difference = floor($days_difference / 7); // Complete weeks
                $first_day        = date("w", $datefrom);
                $days_remainder   = floor($days_difference % 7);
                $odd_days         = $first_day + $days_remainder; // Do we have a Saturday or Sunday in the remainder?

                if ($odd_days > 7) { // Sunday
                    $days_remainder--;
                }

                if ($odd_days > 6) { // Saturday
                    $days_remainder--;
                }

                $datediff = ($weeks_difference * 5) + $days_remainder;
                break;

            case "ww": // Number of full weeks
                $datediff = floor($difference / 604800);
                break;

            case "h": // Number of full hours
                $datediff = floor($difference / 3600);
                break;

            case "n": // Number of full minutes
                $datediff = floor($difference / 60);
                break;

            default: // Number of full seconds (default)
                $datediff = $difference;
                break;
        }

        return $datediff;
    }

    public function channelClear($campaignID,$start_date,$end_date, $optionCheck, $channelName){


        $channel = new CampaignChannel;
        $channel->campaignID = $campaignID;
        $channel->name = $channelName;
        $channel->startDate = date("Y-m-d", strtotime($start_date));
        $channel->endDate = date("Y-m-d", strtotime($end_date));
        $channel->hasExtraWeek = ($optionCheck == "true") ? 1 : 0;
        $channel->save();


        $newVersion = new CampaignChannelVersion;
        $newVersion->versionNumber = "V 1.0";
        $newVersion->channelID = $channel->ID;
        $newVersion->userID = auth::user()->ID;
        $newVersion->changedDateTime = date("Y-m-d H:i", time());
        $newVersion->save();


    }

    public function channelUpdate($campaignID, $dt_start_date, $dt_end_date, $optionCheck, $active_channel){

            $channel = CampaignChannel::where('campaignID', $campaignID)->where('name', $active_channel)->first();
            $channel->name = $active_channel;
            $channel->startDate = date("Y-m-d", strtotime($dt_start_date));
            $channel->endDate = date("Y-m-d", strtotime($dt_end_date));
            $channel->hasExtraWeek = ($optionCheck == "true") ? 1 : 0;
            $channel->save();


        $cnt = CampaignChannelVersion::where('channelID',$channel->ID)->count();

        if($cnt == 0) {
            $version = new CampaignChannelVersion;
            $version->versionNumber = "V 1.0";
            $version->channelID = $channel->ID;
            $version->userID = auth::user()->ID;
            $version->changedDateTime = date("Y-m-d H:i", time());
            $version->save();
        }else{
            $version = CampaignChannelVersion::where('channelID',$channel->ID)->first();
        }
        if(!isset($version->versionNumber)){
            $version->versionNumber = "V 1.0";
        }
        $str = explode('.', $version->versionNumber);
        $version->versionNumber = $str[0].".".($str[1]+1);
        $version->userID = auth::user()->ID;
        $version->changedDateTime = date("Y-m-d H:i", time());
        $version->save();

        $startDate = $channel->startDate;
        $endDate = $channel->endDate;

        $totalWeek = intval($this->datediff("ww",$startDate,$endDate));

        $hasExtra = $channel->hasExtraWeek;
        $startDay = getdate(strtotime($startDate));
        $startWeek = date("W", strtotime($startDate));
        $endDay = getdate(strtotime($endDate));
        if ($startDay["yday"] > $endDay["yday"]) {
            $endWeek = floor(($endDay["yday"] + 365) / 7) + $hasExtra;
        } else {
            $endWeek = date("W", strtotime($endDate)) + $hasExtra;
        }
        if(($startDay["year"] != $endDay["year"])
            && ($startDay["year"] == 2020 || $startDay["year"] == 2026 || $startDay["year"] == 2032) ){
            $endWeek ++;
        }

        foreach ($channel->campaignChannelmediaconcategory as $category) {

            foreach ($category->campaignchannelmedia as $media) {
                if ($media->channeldistribution) {
                    foreach ($media->channeldistribution as $dist) {
                        $dist->delete();
                    }
                }

                if($active_channel == 'ambient' || $active_channel == 'online'  || $active_channel == 'tv') {
                    if($active_channel == 'tv')
                        $distributionCount =  intval($media->grps / ($endWeek - $startWeek + 1 - $hasExtra));
                    else
                        $distributionCount =  intval($media->adPressureValue / ($endWeek - $startWeek + 1 - $hasExtra));

                    for ($i = $startWeek; $i <= ($endWeek - $hasExtra); $i++) {

                        $dist = new CampaignChannelDistribution;
                        $dist->mediaID = $media->ID;

                        $dist->distributionCount =  $distributionCount;

                        $dist->weekNumber = $i;
                        $dist->save();
                    }
                }
                if( $active_channel == 'print'
                    || $active_channel == 'plakat' || $active_channel == 'kino' || $active_channel == 'radio' ) {

                    for ($i = $startWeek; $i <= ($endWeek - $hasExtra); $i++) {

                        $dist = new CampaignChannelDistribution;
                        $dist->mediaID = $media->ID;

                        $dist->distributionCount =  "";

                        $dist->weekNumber = $i;
                        $dist->save();
                    }
                }

            }
        }
    }

    public function channelDelete($campaignID,  $active_channel){
        $channel = CampaignChannel::where('campaignID',$campaignID)->where('name' , $active_channel)->first();

        if ($channel) {
            $channelID = $channel->ID;
            CampaignChannelVersion::where('channelID',$channel->ID)->first()->delete();
            CampaignChannelParameter::where('channelID', $channelID)->delete();
            CoreServiceGroupItem::where('channelID', $channelID)->delete();
            CoreServiceGroup::where('channelID', $channelID)->delete();
            $channel->delete();
        }
    }

}
