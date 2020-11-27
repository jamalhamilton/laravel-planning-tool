<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Campaign;
use App\Models\Client;
use App\Models\CampaignChannel;
use App\Models\CampaignChannelDistribution;
use App\Models\CampaignChannelMediaConCategory;
use App\Models\CampaignChannelVersion;
use App\Models\CoreCampaignCategory;
use App\Models\CoreRegion;
use App\Models\CoreServiceGroup;
use Illuminate\Support\Facades\DB;
use PDF;
use LynX39\LaraPdfMerger\Facades\PdfMerger;
use ZipArchive;


class PdfController extends Controller
{

    public static function prettyPrint($d) {
    $i = (int) $d;
    return $d == $i ? number_format($d,0,".", "'"):number_format($d,2,".", "'");
    }
    protected function generatePdf(Request $request)
    {
        $totalStartTime = 9000000000;
        $totalEndTime = 0;

        $campaignID = $request->id;

        $campaign = Campaign::where('ID', $campaignID)->first();

        $campaignName = urldecode($campaign->name);
        //    $campaignComments = $campaign->comments;
        $campaignComments = $request->comments;
        if(!isset($campaignComments)){
            $campaignComments = "";
        }

        $customerID = -1;
        if ($campaign) {
            $customerID = $campaign->clientID;
        }

        /* -------------------PDF first page calculation------------------------ */

        // overview  Service-Kosten items for all channels
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

            $serviceCosts_channel[$channel->name] = array();

            foreach ($serviceGroups as $serviceGroup) {
                $query = "select SUM(t3.calcValue) as subTotal
                        from core_service_groups_items t1
                        left join campaign_channels_parameters t3 on t1.ID = t3.serviceItemID
                        where t3.channelID = ? and t1.groupID = ? group by t1.groupID";

                $res = DB::select($query, [$onlineChannelID, $serviceGroup['ID']]);

                $query_all = "select *
                        from core_service_groups_items t1
                        left join campaign_channels_parameters t3 on t1.ID = t3.serviceItemID
                        where t3.channelID = ? and t1.groupID = ?";

                $resdes = [];
                $res_all = DB::select($query_all, [$onlineChannelID, $serviceGroup['ID']]);
                $totalFlatrate = 0;

                foreach ($res_all as $k=>$v){
                    if($v->isFlatrate){
                        $totalFlatrate+= $v->calcValue;
                    }else{
                        if($v->value){
                            $clientconservice = DB::select("select * from clients_con_services_values WHERE ID = ?", [$v->clientServiceItemID]);
                            if($clientconservice){
                                if(isset($resdes[$clientconservice[0]->value])){
                                    $resdes[$clientconservice[0]->value] += $v->value;
                                }else{
                                    $resdes[$clientconservice[0]->value.""] = $v->value;
                                }

                            }

                        }
                    }

                }
                if($totalFlatrate>0){
                    $resdes[""] = $totalFlatrate;
                }



//                dd($res_all);
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
                    'subTotal' => floatval($subTotal),
                    'description'=>$resdes
                );

                $serviceCosts_channel_tmp[$serviceGroup["name"]] = 0;

                $serviceTotal += floatval($subTotal);

                array_push($serviceCosts_channel[$channel->name] , $cost);
            }
        }
        //dd($serviceCosts_channel); //luc
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
//        print_r($serviceCosts_channel);
//        dd($serviceCosts);
        //////////////////////////////////////////////

        // overview Mediakosten items for all channels
        $mediaCosts = array();
        $mediaTotal = 0;
        $nnCHFTotal = 0;
        foreach ($channels as $channel) {


            $version = CampaignChannelVersion::where('channelID', $channel->ID)->orderBy('changedDateTime','desc')->first();
            if ($version) {
                $formattedDate = date("d.m.Y", strtotime($version->changedDateTime));
                $channelVersion = $version->versionNumber;
            } else {
                $formattedDate = date("d.m.Y",time());
                $channelVersion = "V 1.0";
            }

            $query = "select SUM(t1.adPressureValue) as subTotal, SUM(t1.nnCHF) as nnCHF
                    from campaign_channels_media t1
                    left join campaign_channels_media_con_categories t2
                    on t1.categoryID = t2.ID
                    where t2.channelID = ?";

            $media = DB::select($query,[$channel->ID]);

            $subTotal = (count($media) > 0) ? $media[0]->subTotal : "0.00";
            $nnCHF = (count($media) > 0) ? $media[0]->nnCHF : "0.00";

            $mediaTotal += floatval($subTotal);
            $nnCHFTotal += floatval($nnCHF);

            $cost = array(
                'channelName' => $channel->name,
                'channelLatestVersion' => $channelVersion,
                'channelVersionDate' => $formattedDate,
                'subTotal' => floatval($subTotal),
                'nnCHF' => floatval($nnCHF)
            );

            array_push($mediaCosts, $cost);
        }
        $total = floatval($nnCHFTotal) + floatval($serviceTotal);

        // service percentage calc
        foreach($serviceCosts as $key => $serviceCost) {
            if ($serviceTotal == 0) {
                $serviceCosts[$key]["percentage"] = "0.00";

            } else {
                $serviceCosts[$key]["percentage"] = number_format(floatval($serviceCost["subTotal"]) / floatval($total) * 100, 2);
            }

            $serviceCosts[$key]["subTotal"] = number_format($serviceCost["subTotal"], 2,".","'");
        }

        $serviceData = array('total' => number_format(floatval($serviceTotal),2,".","'"),'serviceCosts' => $serviceCosts);

        foreach ($mediaCosts as $key => $mediaCost) {
            if ($nnCHFTotal == 0) {
                $mediaCosts[$key]["percentage"] = "0.00";
            } else {
                if($nnCHFTotal != 0)
                    $mediaCosts[$key]["percentage"] =
                        number_format(floatval($mediaCost["nnCHF"]) / floatval($total) * 100, 2);
                else
                    $mediaCosts[$key]["percentage"] = 0;
            }

            $mediaCosts[$key]["subTotal"] = number_format($mediaCost["nnCHF"], 2,".","'");
        }

        $mediaData = array("total" => number_format(floatval($nnCHFTotal), 2,".","'"),"mediaCosts" => $mediaCosts);

        if($total == 0){ $cost_percentage = 0; } else{ $cost_percentage = number_format($serviceTotal * 100 / $total , 2); }

        $vat = config('constant.VAT');
        $totalMWST = ($total * (100 + $vat)) / 100;
        if ($total == 0) {
            $serviceData["percentage"] = "0.0"; $mediaData["percentage"] = "0.0";
        } else {
            $serviceData["percentage"] = number_format(floatval($serviceTotal) / floatval($total) * 100, 2);
            $mediaData["percentage"] = number_format(floatval($nnCHFTotal) / floatval($total) * 100, 2);
        }

        $type = $request->type;

        if ($type == 'internal'){
            $file_name = 'F'.$campaignID.'_'.str_slug($campaignName,'-').'_'.date("d.m.Y").'.pdf';
        }

        if ($type == 'external') {
            $eVersion = $request->version;
            $eDate = $request->date;
            $file_name = 'F'.$campaignID.'_'.str_slug($campaignName,'-').'_'.$this->str_clean($eVersion).'_'.$eDate.'.pdf';

        }

        $firstTable = array();
        foreach ($mediaData['mediaCosts'] as $key => $mediaCost) {
            if($mediaCost['channelName'] == 'tv') {
                $row = array(
                    'TV',
                    $mediaCost['channelLatestVersion'] . ' / ' . $mediaCost['channelVersionDate'],
                    $mediaCost['subTotal'],
                    $mediaCost['percentage']
                );
            }
            else{
                $row = array(
                    ucfirst($mediaCost['channelName']),
                    $mediaCost['channelLatestVersion'] . ' / ' . $mediaCost['channelVersionDate'],
                    $mediaCost['subTotal'],
                    $mediaCost['percentage']
                );
            }
            array_push($firstTable, $row);
        }

        $secondTable = array();

        foreach ($serviceData['serviceCosts']  as $idx => $serviceCost) {
            $row = array(
                $serviceCost['groupName'],
                '',
                $serviceCost['subTotal'],
                $serviceCost['percentage']
            );

            array_push($secondTable, $row);
        }

        foreach($channels as $channel) {
            $starttime = strtotime($channel->startDate);
            $endtime = strtotime($channel->endDate);

            if (($channel->name == 'online' || $channel->name == 'ambient') && $channel->hasExtraWeek == 1) {
                $calendarDate = date_create($channel->endDate);
                date_add($calendarDate, date_interval_create_from_date_string("7 days"));

                if ($endtime < strtotime(date_format($calendarDate, "Y-m-d"))) {
                    $endtime = strtotime(date_format($calendarDate, "Y-m-d"));
                }
            }

            if ($starttime < $totalStartTime) $totalStartTime = $starttime;
            if ($endtime > $totalEndTime) $totalEndTime = $endtime;
        }
        if ($totalStartTime == 9000000000) $totalStartTime = time();
        if ($totalEndTime == 0) $totalEndTime = time();

        $header = array( $campaignName, date("d.m.Y",$totalStartTime) . ' - ' . date("d.m.Y",$totalEndTime));

        if ($type == 'internal'){ array_push($header, date("d.m.Y")); }

        if ($type == 'external') {
            $eVersion = $request->version;
            $eDate = $request->date;
            array_push($header, $eVersion );
            array_push($header, $eDate);
        }

        $data1 = [
            'type' => $type,
            'header' => $header,
            'file_name'=>$file_name,
            'first_table' => $firstTable,
            'first_table_total' => array('Total Mediakosten', '', $mediaData['total'], $mediaData['percentage']),
            'second_table' => $secondTable,
            'second_table_total' => array('Total Servicekosten', '', $serviceData['total'], $serviceData['percentage']),
            'total_table' => array(
                array('Total ohne MWST', number_format($total, 2,".","'"), '100.00'),
                array('Total inkl. MWST', number_format($totalMWST,2,".","'"), number_format(100.00 + $vat,2))
            ),
            'bemerkungen' => $campaignComments
        ];
        /* --------------------------------End of first page----------------------------*/



        /* -------------- PDF second page start --------------------------- */

        $data2 = array();
        foreach($channels as $channel) {

            $starttime = strtotime($channel->startDate);
            $endtime = strtotime($channel->endDate);


            $header = array( $campaignName, date("d.m.Y",$starttime) . ' - ' . date("d.m.Y",$endtime));
          //  array_push($header, date("d.m.Y"));

            $version = CampaignChannelVersion::where('channelID', $channel->ID)->orderBy('changedDateTime','desc')->first();
            if ($version) {
                $channelVersion = $version->versionNumber;
            } else {
                $channelVersion = "V 1.0";
            }

            if ($type == 'internal'){
                array_push($header, date("d.m.Y"));
            }

            if ($type == 'external') {

                array_push($header, $channelVersion );
                array_push($header, date("d.m.Y"));
            }


            $nameArr = array();
            $mediaArr = array();
            $disArr = array();
            $disSumArr = array();
            $totalDisSum = array();
            $adWeekArr = array();

            $dch_f = 0 ; $fch_f =0; $ich_f = 0 ; $dfch_f = 0; $national_f = 0;
            $adp = array('dch' => 0, 'fch' => 0, 'ich' => 0, 'dfch' => 0, 'national' => 0);
            $nn =  array('dch' => 0, 'fch' => 0, 'ich' => 0, 'dfch' => 0, 'national' => 0);
            $tkpnn =  array('dch' => 0, 'fch' => 0, 'ich' => 0, 'dfch' => 0, 'national' => 0);

            $totalNN = 0;
            $categories = CampaignChannel::where('campaignID', $campaignID)->where('name', $channel->name)->first()->campaignChannelmediaconcategory;
            foreach ($categories as $category) {
                $categoryName = $category->corecampaigncategory->name;
                $medias = $category->campaignchannelmedia;

                $mediaData = array();
                $disData = array();
                $disSum = array();
                $adWeek = array();

                $index = 0;

                foreach ($medias as $media) {
                    $notes = array();

                    foreach ($media->campaignchannelmedianote as $note) {
                        $notes[$note->colOrder] = $note->note;
                    }

                    $region = '';
                    $coreregion_name = [];
                    $tmp = explode(', ',$media->regionID);
                    foreach ($tmp as $tmpid){
                        $rtemp = CoreRegion::where('ID', intval($tmpid))->first();
                        $coreregion_name[] = $rtemp['name'];
                    }
                    if(!empty($coreregion_name)){
                        $region = implode(', ',$coreregion_name);
                    }

                    $format = $media->formatValue;

                    if ($format == '') {
                        if (isset($media->scoreadformat->name)) {
                            $format = $media->scoreadformat->name;
                        }
                    }
                    if(!empty($coreregion_name)){
                        foreach ($coreregion_name as $reg){
                            switch ($reg) {
                                case 'D-CH':
                                    $dch_f = 1;
                                    $adp['dch'] += $media->adPressureValue;
                                    $nn['dch'] += $media->nnCHF;
                                    $tkpnn['dch'] += $media->tkpNNCHF;
                                    break;
                                case 'F-CH':
                                    $fch_f = 1;
                                    $adp['fch'] += $media->adPressureValue;
                                    $nn['fch'] += $media->nnCHF;
                                    $tkpnn['fch'] += $media->tkpNNCHF;
                                    break;
                                case 'I-CH':
                                    $ich_f = 1;
                                    $adp['ich'] += $media->adPressureValue;
                                    $nn['ich'] += $media->nnCHF;
                                    $tkpnn['ich'] += $media->tkpNNCHF;
                                    break;
                                case 'D-/F-CH':
                                    $dfch_f = 1;
                                    $adp['dfch'] += $media->adPressureValue;
                                    $nn['dfch'] += $media->nnCHF;
                                    $tkpnn['dfch'] += $media->tkpNNCHF;
                                    break;
                                case 'National':
                                    $national_f = 1;
                                    $adp['national'] += $media->adPressureValue;
                                    $nn['national'] += $media->nnCHF;
                                    $tkpnn['national'] += $media->tkpNNCHF;
                                    break;
                            }
                        }
                    }


                    $mediaData[$index] = array(
                        $media->placing,
                        $media->details,
                        $region,
                        $format,
                        $media->adPressureValue,
                        $media->tkpGrossCHF,
                        $media->grossCHF,
                        $media->discountPersentual,
                        $media->netCHF,
                        $media->bkPersentual,
                        $media->tkpNNCHF,
                        $media->nnCHF,
                        '(' . $media->ID . ')',
                        $media->grps,
                        $media->kontaktsumme,
                        $media->is_cpc,
                        $media->ad_impressions,
                    );
                    $totalNN += $media->nnCHF;

                    $dists = CampaignChannelDistribution::where('mediaID', $media->ID)->get();

                    $adWeekSum = 0;
                    $disCount = array();
                    if (sizeof($disSum) == 0) {
                        for ($i = 0; $i < sizeof($dists); $i++) {
                            $disSum[$i] = 0;
                        }
                    }

                    if($channel->name == 'online' || $channel->name == 'tv' || $channel->name == 'ambient'){

                        $i = 0;
                        foreach ($dists as $dist) {

                            array_push($disCount, number_format(intval($dist->distributionCount), 0, '.', '\''));
                            $disSum[$i++] += intval($dist->distributionCount);
                            $adWeekSum += intval($dist->distributionCount);
                        }
                    } else{
                        $i = 0;
                        foreach ($dists as $dist) {
                            array_push($disCount, $dist->distributionCount);
                            @$disSum[$i++] += 0; $adWeekSum += 0;
                        }
                    }

                    $disData[$index] = $disCount;
                    $adWeek[$index] = $adWeekSum;
                    $index++;
                }

                if (sizeof($totalDisSum) == 0) {
                    for ($i = 0; $i < sizeof($disSum); $i++) {
                        $totalDisSum[$i] = 0;
                    }
                }

                for ($i = 0; $i < sizeof($disSum); $i++) {
                    $totalDisSum[$i] += $disSum[$i];
                }

                array_push($nameArr, $categoryName);
                array_push($mediaArr, $mediaData);
                array_push($disArr, $disData);
                array_push($disSumArr, $disSum);
                array_push($adWeekArr, $adWeek);
            }

            // get startdate and enddate
            $onlineChannel = CampaignChannel::where('name', $channel->name)->where('campaignID', $campaignID)->first();
            $startDate = date("Y-m-d", time());
            $endDate = date("Y-m-d", time());

            if ($onlineChannel) {
                $startDate = $onlineChannel->startDate;
                $endDate = $onlineChannel->endDate;
                $hasExtra = $onlineChannel->hasExtraWeek;
            }

            ///////////////////////////

            $startDay = getdate(strtotime($startDate));
            $startWeek = date("W", strtotime($startDate));
            $endDay = getdate(strtotime($endDate));
            $endWeek = date("W", strtotime($endDate)) + $hasExtra;

            if ($endWeek < $startWeek) {
                $endWeek = ceil(($endDay["yday"] + 365) / 7) + $hasExtra;
            }

            $startWDay = ($startDay["wday"] == 0) ? 7 : $startDay["wday"];
            $date = date_create();
            date_timestamp_set($date, ($startDay[0] - 60 * 60 * 24 * ($startWDay - 1)));
            $totalWeek = intval($this->datediff("ww", $startDate, $endDate));

            $week_nums = 0;

            $dateArr = array();

            if ($startDay["yday"] > $endDay["yday"]) {
                $endWeek = floor(($endDay["yday"] + 365) / 7) + $hasExtra;
            } else {
                $endWeek = date("W", strtotime($endDate)) + $hasExtra;
            }
            if(($startDay["year"] != $endDay["year"])
                && ($startDay["year"] == 2020 || $startDay["year"] == 2026 || $startDay["year"] == 2032) ){
                $endWeek ++;
            }

            $totalWeek = ($endWeek - $startWeek + 1 - $hasExtra);

            for ($i = 0; $i < $totalWeek; $i++) {
                $d = $i * 7;
                $weekNumber = date("W", strtotime("+$d days", strtotime($startDate)));
                array_push($dateArr, "KW " . $weekNumber . "<br/>(" . date_format($date, "d.m") . ")");
                date_add($date, date_interval_create_from_date_string("7 days"));
            }
            if ($hasExtra) {
                $d = $i * 7;
                $weekNumber = date("W", strtotime("+$d days", strtotime($startDate)));
                array_push($dateArr, "KW " . $weekNumber . "<br/>(" . date_format($date, "d.m") . ")");
                date_add($date, date_interval_create_from_date_string("7 days"));
            }

            ////////////////////////////

            $sumAdp = $adp['dch'] + $adp['fch'] + $adp['ich'] + $adp['dfch'] + $adp['national'];
            $sumAdp2 = $nn['dch'] + $nn['fch'] + $nn['ich'] + $nn['dfch'] + $nn['national'];

            $dch = $sumAdp != 0 ? (100 * $adp['dch'] / $sumAdp) : 0;
            $fch = $sumAdp != 0 ? (100 * $adp['fch'] / $sumAdp) : 0;
            $ich = $sumAdp != 0 ? (100 * $adp['ich'] / $sumAdp) : 0;
            $dfch = $sumAdp != 0 ? (100 * $adp['dfch'] / $sumAdp) : 0;
            $national = $sumAdp != 0 ? (100 * $adp['national'] / $sumAdp) : 0;

            $dch2 = $sumAdp2 != 0 ? (100 * $nn['dch'] / $sumAdp2) : 0;
            $fch2 = $sumAdp2 != 0 ? (100 * $nn['fch'] / $sumAdp2) : 0;
            $ich2 = $sumAdp2 != 0 ? (100 * $nn['ich'] / $sumAdp2) : 0;
            $dfch2 = $sumAdp2 != 0 ? (100 * $nn['dfch'] / $sumAdp2) : 0;
            $national2 = $sumAdp2 != 0 ? (100 * $nn['national'] / $sumAdp2) : 0;

            $total_tkpnn = $tkpnn['dch'] + $tkpnn['fch'] + $tkpnn['ich'] + $tkpnn['dfch'] + $tkpnn['national'];
            $leftTable = array();
            if($dch_f == 1){
                array_push($leftTable,array('D-CH', $adp['dch'], $dch, $nn['dch'], $dch2) );
            }
            if($fch_f == 1){
                array_push($leftTable, array('F-CH', $adp['fch'], $fch, $nn['fch'], $fch2) );
            }
            if($ich_f == 1){
                array_push($leftTable, array('I-CH', $adp['ich'], $ich, $nn['ich'], $ich2) );
            }
            if($dfch_f == 1){
                array_push($leftTable,  array('D-/F-CH', $adp['dfch'], $dfch, $nn['dfch'], $dfch2) );
            }
            if($national_f == 1){
                array_push($leftTable, array('National', $adp['national'], $national, $nn['national'], $national2) );
            }
            array_push($leftTable, array('Total', $sumAdp, '100.00', $sumAdp2, '100.00') );
            ///////////////////////////////service cost recalc for each channel //////////////////////////////////
            $serviceCosts = array();
            $serviceCosts_channel = array();
            $serviceTotal = 0;
            $serviceTotalHours = 0;
            $serviceCosts_channel_tmp = array();

            $onlineChannel = CampaignChannel::where('name',$channel->name)->where('campaignID', $campaignID)->first();
            $onlineChannelID = -1;

            if ($onlineChannel) {
                $onlineChannelID = $onlineChannel->ID;
            }
            // Calculate service costs

            $serviceCosts_channel[$channel->name] = array();

            foreach ($serviceGroups as $serviceGroup) {
                $query = "select SUM(t3.calcValue) as subTotal, SUM(t3.value)
                    from core_service_groups_items t1
                    left join campaign_channels_parameters t3 on t1.ID = t3.serviceItemID
                    where t3.channelID = ? and t1.groupID = ? group by t1.groupID";

                $res = DB::select($query, [$onlineChannelID, $serviceGroup['ID']]);
                $subTotal = (count($res) > 0) ? $res[0]->subTotal : "0.00";

                $resdes = [];
                $query_all = "select *
                        from core_service_groups_items t1
                        left join campaign_channels_parameters t3 on t1.ID = t3.serviceItemID
                        where t3.channelID = ? and t1.groupID = ?";
                $res_all = DB::select($query_all, [$onlineChannelID, $serviceGroup['ID']]);
                $totalFlatrate = 0;

                foreach ($res_all as $k=>$v){
                    if($v->isFlatrate){
                        $totalFlatrate+= $v->calcValue;
                    }else{
                        if($v->value){

                            if($v->clientServiceItemID!= -1){
                            $clientconservice = DB::select("select * from clients_con_services_values WHERE ID = ?", [$v->clientServiceItemID]);

                            if($clientconservice){
                                    $key = $clientconservice[0]->value."";
                                if(isset($resdes[$key])){
                                    $resdes[$key] += $v->value;
                                }else{
                                    $resdes[$key] = $v->value;
                                }

                            }
                            }else{
                                if(!isset($resdes[0])){
                                    $resdes[0] = $v->value;
                                }else{
                                    $resdes[0] += $v->value;
                                }

                            }


                        }
                    }

                }
                if($totalFlatrate>0){
                    $resdes[""] = $totalFlatrate;
                }
                //print_r($resdes);
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
                    'subTotal' => floatval($subTotal),
                    'resdes'=> $resdes
                );
//                print_r($resdes);

                $serviceCosts_channel_tmp[$serviceGroup["name"]] = 0;
                $serviceCosts_channel_tmp[$serviceGroup["name"]] = 0;

                $serviceTotal += floatval($subTotal);

                array_push($serviceCosts_channel[$channel->name] , $cost);
            }

            $service_total = 0;
            $rightTable = array();

//            dd($serviceCosts_channel);
            $tmp_des = [];

            foreach ($serviceCosts_channel as $chn_name => $costs_ar) {
                foreach($costs_ar as $cost){
                    $serviceCosts_channel_tmp[$cost['groupName']] += $cost['subTotal'];
                    $tmp_des[$cost['groupName']] = $cost['resdes'];
                    $service_total += $cost['subTotal'];
                }
            }
//            dd($tmp_des);
//            dd($serviceCosts_channel_tmp);
            foreach ($serviceCosts_channel_tmp as $groupNm => $value) {
                //$groupNm = $this->indexToGroupName($serviceCosts_channel, $index);
                $percent = 0;
                if($service_total != 0){
                    $percent = number_format(100*(floatval($value)/floatval($service_total)), 2);
                }
                $cost = array(
                    'groupName' =>$groupNm,
                    'subTotal' => number_format($value, 2),
                    'percentage' => $percent,
                    'des'=>$tmp_des[$groupNm]
                );
                array_push($serviceCosts, $cost);
                $descript = [];
                //print_r($groupNm);print_r($tmp_des[$groupNm]);
                if($tmp_des[$groupNm]) {
                    foreach ($tmp_des[$groupNm] as $m => $n){

                        if ($groupNm == 'Media-Honorar') {
                            if ($m != "") {
                                $descript[] = ' '.$m.'%';
                            } else {
                                $descript[] = 'Pauschale CHF '.$this->prettyPrint($n).' ';
                            }
                        } elseif ($groupNm == 'Technische Kosten') {
                            if ($m != "")
                                $descript[] = ' TKP CHF '.$m;
                            else
                                $descript[] = 'Pauschale CHF '.$this->prettyPrint($n).' ';

                        } else {
                            if ($m !== "")
                                $descript[] = ' '.$this->prettyPrint($n).'h x CHF '.$this->prettyPrint($m).' ';
                            else
                                $descript[] = 'Pauschale CHF '.$this->prettyPrint($n).' ';
                        }
                    }


                }


                $row = array($groupNm,
                    $this->prettyPrint($value),
                    $percent,
                    $subTotal,
                    $descript
                );
                if($groupNm == 'Abzüge' && $subTotal == 0) continue;

                array_push($rightTable, $row);
            }

        //    $service_total += $totalNN;
            $total = $service_total + $totalNN;
            $service_percentage = 0;
            if($total != 0) {
                $service_percentage = number_format(100 * floatval($service_total) / $total, 2);
            }

            array_push($rightTable, array('Total Servicekosten exkl. MWST', $this->prettyPrint($service_total), $service_percentage,'',[]));
            array_push($rightTable, array('Total Media- & Servicekosten exkl. MWST.', number_format($total, 2, ".", "'"), '100.00','',[]));
            //dd($rightTable);
            $diff = sizeof($rightTable) - sizeof($leftTable) - 1;

            $empty = array();

            for ($i = 0; $i < $diff; $i++) ;
            array_push($empty, array('', '', '', '', ''));

            if($channel->name == 'online' || $channel->name == 'ambient') {
                array_push($empty, array('SPRACHE', 'WERBEDRUCK', 'ANTEIL IN %', 'KOSTEN N/N', 'ANTEIL IN %'));
            }
            if($channel->name == 'kino') {
                array_push($empty, array('SPRACHE', 'KONTAKTE', 'ANTEIL IN %', 'KOSTEN N/N', 'ANTEIL IN %'));
            }
            if($channel->name == 'print') {
                array_push($empty, array('SPRACHE', 'SCHALTUNGEN', 'ANTEIL IN %', 'KOSTEN N/N', 'ANTEIL IN %'));
            }
            if($channel->name == 'plakat') {
                array_push($empty, array('SPRACHE', 'STELLEN / SCREENS', 'ANTEIL IN %', 'KOSTEN N/N', 'ANTEIL IN %'));
            }
            if($channel->name == 'tv' || $channel->name == 'radio') {
                array_push($empty, array('SPRACHE', 'AUSSTRAHLUNGEN', 'ANTEIL IN %', 'KOSTEN N/N', 'ANTEIL IN %'));
            }

            $leftTable = array_merge($empty, $leftTable);

            $data2[$channel->name] = [
                'type' => $type,
                'header' => $header,
                'name' => $nameArr,
                'media' => $mediaArr,
                'discount' => $disArr,
                'disSum' => $disSumArr,
                'totalDisSum' => $totalDisSum,
                'hasExtra' => $hasExtra,
                'adweek' => $adWeekArr,
                'date' => $dateArr,
                'leftTable' => $leftTable,
                'rightTable' => $rightTable,
                'diff' => $diff
            ];

//            dd($data2);

            $customer = Client::where('ID', $customerID)->first();

            if (isset($customer->logo)) {
                $data1['logo'] = $customer->logo;
                $data2[$channel->name]['logo'] = $customer->logo;
            } else {
                $data1['logo'] = '/zurcher_logo.png';
                $data2[$channel->name]['logo'] = '/zurcher_logo.png';
            }
        }
        return $this->generator($data1, $data2, $channels);
    }

    protected function generator($data1, $data2, $channels){

        $customPaper = array(0,0,3509,4962);
        $merg = array();
        $data3 = array();

        $pdf1 = PDF::loadView('pdf.overview', $data1, $merg)->setPaper($customPaper, 'portarit');
        $pdf1->save(public_path().'/uploads/pdf/templates/overview.pdf');

        $customPaper = array(0, 0, 3509, 4962);
        $page = 2;
        $totalPage = 1;

        //// split the pdf when height is overflow -- over 13 datatable data ////////
        foreach($channels as $channel) {
            $mediaSize = 0;

            $new_media = array();
            $new_mediaArray = array();

            $new_mediaName = array();
            $new_mediaNameArray = array();

            $new_discount = array();
            $new_discountArray = array();

            $new_disSum = array();
            $new_disSumArray = array();

            $new_adweek = array();
            $new_adweekArray = array();

            $mediaPage = 0;

            foreach($data2[$channel->name]['media'] as $key => $mediaItem){
                if($mediaSize + sizeof($mediaItem) > 11 * ($mediaPage + 1)){
                    array_push($new_mediaArray, $new_media);
                    array_push($new_mediaNameArray, $new_mediaName);
                    array_push($new_discountArray, $new_discount);
                    array_push($new_disSumArray, $new_disSum);
                    array_push($new_adweekArray, $new_adweek);

                    $new_media = array();
                    $new_mediaName = array();
                    $new_discount = array();
                    $new_disSum = array();
                    $new_adweek = array();

                    array_push($new_media, $mediaItem);
                    array_push($new_mediaName, $data2[$channel->name]['name'][$key]);
                    array_push($new_discount, $data2[$channel->name]['discount'][$key]);
                    array_push($new_disSum, $data2[$channel->name]['disSum'][$key]);
                    array_push($new_adweek, $data2[$channel->name]['adweek'][$key]);

                    $mediaPage ++;
                }
                else{
                    array_push($new_media, $mediaItem);
                    array_push($new_mediaName, $data2[$channel->name]['name'][$key]);
                    array_push($new_discount, $data2[$channel->name]['discount'][$key]);
                    array_push($new_disSum, $data2[$channel->name]['disSum'][$key]);
                    array_push($new_adweek, $data2[$channel->name]['adweek'][$key]);
                }

                $mediaSize += sizeof($mediaItem);
            }
            array_push($new_mediaArray, $new_media);
            array_push($new_mediaNameArray, $new_mediaName);
            array_push($new_discountArray, $new_discount);
            array_push($new_disSumArray, $new_disSum);
            array_push($new_adweekArray, $new_adweek);

            $data3[$channel->name] = array();
            $i = 0;
            foreach($new_mediaArray as $key => $mediaItem){
                $channelData = $data2[$channel->name];
                $channelData['media'] = $mediaItem;
                $channelData['name'] = $new_mediaNameArray[$key];
                $channelData['discount'] = $new_discountArray[$key];
                $channelData['disSum'] = $new_disSumArray[$key];
                $channelData['adweek'] = $new_adweekArray[$key];
                if($i == 0){
                    $channelData['is_first'] = 1;
                }
                else{
                    $channelData['is_first'] = 0;
                }
                array_push($data3[$channel->name], $channelData);
                $i ++;
            }
        }
        //////////////////////////

        foreach($channels as $channel) {
            foreach($data3[$channel->name] as $data3Item) {
                $totalPage += ceil(sizeof($data3Item['date']) / 16);
            }
        }
        $channel_pagesize = 0;
        foreach($channels as $channel) {
            foreach($data3[$channel->name] as $key => $data3Item) {
                $calc_margin_top = 17 + ($data3Item['diff'] - 1) * 76;

                $page += $channel_pagesize;

                $data3Item['page_number'] = $page;
                $data3Item['calc_margin_top'] = $calc_margin_top;
                $data3Item['totalPage'] = $totalPage;
                $data3Item['isViewMediaId'] = false;

                $data3[$channel->name][$key] = $data3Item;
                $pdf2 = PDF::loadView('pdf.planing_' . $channel->name, $data3Item, $merg)->setPaper($customPaper, 'landscape');
                $pdf2->save(public_path() . '/uploads/pdf/templates/planing_' . $channel->name .$key. '.pdf');

                $channel_pagesize = ceil(sizeof($data3Item['date']) / 16);
            }
        }

        // internal mode pdf generation
        $pdfMerger = PDFMerger::init();
        $pdfMerger->addPDF(public_path() . '/uploads/pdf/templates/overview.pdf', 'all');
        foreach($channels as $channel) {
            foreach($data3[$channel->name] as $key => $data3Item) {
                $pdfMerger->addPDF(public_path() . '/uploads/pdf/templates/planing_' . $channel->name. $key . '.pdf');
            }
        }
        $pdfMerger->merge();
        $file_name = $data1['file_name'];
        $pdfMerger->save(public_path() . '/uploads/pdf/' . $file_name, 'file');
        ////

        if ($data1['type'] == 'external') {

            $customPaper = array(0, 0, 3509, 4962);
            foreach($channels as $channel) {

                foreach($data3[$channel->name] as $key => $data3Item) {

                    $data3Item['isViewMediaId'] = true;
                    $pdf2 = PDF::loadView('pdf.planing_' . $channel->name, $data3Item, $merg)->setPaper($customPaper, 'landscape');
                    $pdf2->save(public_path() . '/uploads/pdf/templates/planing_id_' . $channel->name . $key . '.pdf');
                }
            }

            //pdf2
            $pdfMerger = PDFMerger::init();
            $pdfMerger->addPDF(public_path() . '/uploads/pdf/templates/overview.pdf', 'all');
            foreach($channels as $channel) {
                foreach($data3[$channel->name] as $key => $data3Item) {
                    $pdfMerger->addPDF(public_path() . '/uploads/pdf/templates/planing_id_' . $channel->name . $key . '.pdf');
                }
            }
            $pdfMerger->merge();
            $file_name = $data1['file_name'];
            $pdfMerger->save(public_path() . '/uploads/pdf/' . 'ID_' . $file_name, 'file');

            $archive_file_name = $file_name . '.zip';
            $zip = new ZipArchive();
            if ($zip->open(public_path() . '/uploads/pdf/' . $archive_file_name, ZIPARCHIVE::CREATE) !== TRUE) {

            }

            $zip->addFile(public_path() . '/uploads/pdf/' . $file_name, $file_name);
            $zip->addFile(public_path() . '/uploads/pdf/' . 'ID_' . $file_name, 'ID_' . $file_name);
            $zip->close();

            $file_name = $archive_file_name;
        }

        return redirect('download/pdf/'.$file_name);

    }

    public function calculateadPSum($campaignID, $channel_name){
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

        //  return number_format($sum, 2, '.', '\'');
        return $sum;
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

        //    return number_format($sum, 2, '.', '\'');
        return $sum;
    }

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
                $quarters_difference = floor($difference / 8034700);

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
                $datediff = floor($difference / 604700);
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
}
