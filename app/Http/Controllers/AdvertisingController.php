<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Campaign;
use App\Models\CampaignChannel;
use App\Models\CampaignChannelVersion;
use App\Models\CampaignChannelMedia;
use App\Models\CampaignChannelDistribution;

use Illuminate\Http\Request;
use Auth;

class AdvertisingController extends Controller
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
    public function index(Request $request)
    {
        $campaignID = $request->id;
        $campaign = Campaign::where('ID',$campaignID)->first();
        //var_dump($campaign);exit;
        if($campaign == null) return abort(404);
        $campaignName = $campaign->name;

        $active_channel = $request->channel;

        $channels = CampaignChannel::where('campaignID', $campaignID)->get()->toArray();


        $onlineChannel = CampaignChannel::where('campaignID',$campaignID)->where('name',$active_channel)->first();

        $startDate = date("Y-m-d", time());
        $endDate = date("Y-m-d", time());

        if ($onlineChannel) {
            $startDate = $onlineChannel->startDate;
            $endDate = $onlineChannel->endDate;
            $hasExtra = $onlineChannel->hasExtraWeek;
        } else {
            return redirect('planning/overview?id='.$campaignID);
        }   




        //////////////////////////////////
        $startDay = getdate(strtotime($startDate));
        $startWeek = date("W",strtotime($startDate));
        $endDay = getdate(strtotime($endDate));
        $endWeek = date("W",strtotime($endDate)) + $hasExtra;

        if ($endWeek < $startWeek) {
            $endWeek = ceil(($endDay["yday"] + 365) / 7) + $hasExtra;
        }

        $startWDay =  ($startDay["wday"] == 0) ? 7 : $startDay["wday"];
        $date = date_create();
        date_timestamp_set($date,($startDay[0] - 60*60*24*($startWDay - 1)));
        $week_nums = 0;

        $totalWeek = intval($this->datediff("ww",$startDate,$endDate));

        ////////// calculate total weeks ////////////////////
        $hasExtra = $onlineChannel->hasExtraWeek;
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

        $totalWeek = ($endWeek - $startWeek + 1 - $hasExtra);

        for ($i = 0; $i < $totalWeek; $i++) {
            $d = $i*7;
            $weekNumber = date("W",strtotime("+$d days",strtotime($startDate)));

            $dateArray[$i]="KW ".$weekNumber." \n(".date_format($date,"d.m").")";
            $week_nums ++;
            date_add($date,date_interval_create_from_date_string("7 days"));
        }

        if($hasExtra){
            $d = $i*7;
            $weekNumber = date("W",strtotime("+$d days",strtotime($startDate)));

            $dateArray[$i]="KW ".$weekNumber." \n(".date_format($date,"d.m").")";
            $week_nums ++;
            date_add($date,date_interval_create_from_date_string("7 days"));
        }

        $startDay = date_format($date,"m.d");               

        $categories = array();

        $res = CampaignChannel::where('campaignID',$campaignID)->where('name',$active_channel)->first();

        if ($res) {
            $categories = $res->campaignChannelmediaconcategory;
        }

        $data = array();

        foreach ($categories as $category) {
            $categoryID = $category->ID;
            $categoryName = $category->corecampaigncategory->name;
            $categoryNote = $category->categoryNote;
            $medias = $category->campaignchannelmedia;
            $mediaData = array();
            $total = array();
            
            foreach ($medias as $media) {
                $notes = array();

                foreach ($media->campaignchannelmedianote as $note) {
                    $notes[$note->colOrder] = $note->note;
                }

                $dists = CampaignChannelDistribution::where('mediaID',$media->ID)->get();
//                dd($dists);
                $distCount = array();
                $adPrint = number_format($media->adPressureValue, 0, '.', '\'');
                $grps = number_format($media->grps, 0, '.', '\'');

                $adWeekSum = 0;
                foreach ($dists as $dist) {
                    if($active_channel == 'online'  || $active_channel == 'tv' || $active_channel == 'ambient') {

                        if($active_channel == 'tv') {

                            $digit_nums = strlen(substr(strrchr($dist->distributionCount, "."), 1));
                            if($digit_nums > 2) $digit_nums = 2;
                            if($digit_nums == 0) {
                                $distCount[$dist->weekNumber] = number_format(intval($dist->distributionCount), 0, '.', '\'');
                            }
                            else {
                                $distCount[$dist->weekNumber] = number_format(floatval($dist->distributionCount), $digit_nums, '.', '\'');
                            }

                            $adWeekSum += floatval($dist->distributionCount);
                        }
                        else {
                            $distCount[$dist->weekNumber] = number_format(intval($dist->distributionCount), 0, '.', '\'');
                            $adWeekSum += intval($dist->distributionCount);
                        }

                    }
                    if($active_channel == 'print' || $active_channel == 'plakat' || $active_channel == 'kino' || $active_channel == 'radio') {
                        $distCount[$dist->weekNumber] = $dist->distributionCount;
                    }

                }

                $overflow = "#5C7C9D";
                if($active_channel == 'tv'){
                    if ($media->grps != $adWeekSum) {
                        $overflow = "red";
                    }
                }
                else {
                    if ($media->adPressureValue != $adWeekSum) {
                        $overflow = "red";
                    }
                }

                 $digit_nums = strlen(substr(strrchr($adWeekSum, "."), 1));
                if($digit_nums > 2) $digit_nums = 2;
                if($digit_nums == 0) {
                    $adWeekSum = number_format(intval($adWeekSum), 0, '.', '\'');
                }
                else {
                    $adWeekSum = number_format(floatval($adWeekSum), $digit_nums, '.', '\'');
                }

                $mediaData[] = array(
                    'id' => $media->ID,
                    'placement' => $media->placing,
                    'adPrint' => $adPrint,
                    'grps' => $grps,
                    'overflow' => $overflow,
                    'adWeekSum' => $adWeekSum,
                    'distCount' => $distCount,
                );
            }

            $data[] = array(
                'id' => $categoryID,                
                'name' => $categoryName,
                'note' => $categoryNote,
                'media' => $mediaData,
            );
        }

       // $totalWidth = (300 + 120 * ($endWeek - $startWeek + 2));


       // $tableWidth = "width:".$totalWidth."px;";
         $tableWidth = "width:100%;";

        $view_name = '';
        if($active_channel == 'print' || $active_channel == 'plakat' || $active_channel == 'kino') {
            $view_name = 'print_plakat_kino';
        }else{
            $view_name = $active_channel;
        }

        return view('pages.advertising.'.$view_name.'.printing',
                        ['tabIndex' => 1,
                         'activetab' => $active_channel,
                         'campaignID' => $request->id,
                         'campaign_id' => $request->id,
                         'campaignName' => $campaignName,
                         'dateArray' => $dateArray,
                         'week_nums' => $week_nums,
                         'data' => $data,
                         'startWeek' =>$startWeek,
                         'endWeek'=>$endWeek,
                         'tableWidth'=>$tableWidth,
                         'channels' => $channels,
                         'hasExtraWeek' => $hasExtra]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $ignore = 0;
        $active_channel = $request->active_channel;
        $media = CampaignChannelMedia::where('ID',$request->mediaID)->first();
        $remained = $media->adPressureValue;
        if($active_channel == 'tv'){
            $remained = $media->grps;
        }

        if($active_channel == 'online'  || $active_channel == 'tv' ) {

            foreach ($media->channeldistribution as $dist) {
                if ($dist->weekNumber != $request->weekNum) {
                    if($active_channel == 'tv'){
                        $remained -= floatval($dist->distributionCount);
                    }
                    else{
                        $remained -= intval($dist->distributionCount);
                    }
                }
            }



            if ($request->number <= $remained) {

                $dist = CampaignChannelDistribution::where('mediaID', $request->mediaID)->where('weekNumber', $request->weekNum)->first();
                if($dist->distributionCount == $request->number){
                    $ignore = 1;
                }

                $dist->distributionCount = $request->number;
                $dist->save();

                $msg = 'Edit Ok!';

                // plus version number
                $channelID = CampaignChannel::where('campaignID', $request->campaignID)->where('name', $request->active_channel)->first()->ID;
                $channelversion = CampaignChannelVersion::all()->where('channelID', $channelID)->first();
                // var_dump(date("d-m-Y", time()));exit();
                if (!$channelversion) {
                    $newVersion = new CampaignChannelVersion;
                    $newVersion->versionNumber = "V 1.0";
                    $newVersion->channelID = $channelID;
                    $newVersion->userID = auth::user()->ID;
                    $newVersion->changedDateTime = date("Y-m-d", time());
                    $newVersion->save();
                } else if($ignore == 0){
                    $str = array();
                    $str = explode('.', $channelversion->versionNumber);
                    $channelversion->versionNumber = $str[0] . "." . ($str[1] + 1);
                    $channelversion->userID = auth::user()->ID;
                    $channelversion->changedDateTime = date("Y-m-d H:i", time());
                    $channelversion->save();
                }
                ///

            } else {
                $msg = 'Edit Failed!';
            }
        }


        if($active_channel == 'print' || $active_channel == 'plakat' || $active_channel == 'kino' || $active_channel == 'radio' ) {

            $dist = CampaignChannelDistribution::where('mediaID', $request->mediaID)->where('weekNumber', $request->weekNum)->first();
            if($dist->distributionCount == $request->number){
                $ignore = 1;
            }

            $dist->distributionCount = $request->number;
            $dist->save();
            $msg = 'Edit Ok!';

            // plus version number
            $channelID = CampaignChannel::where('campaignID', $request->campaignID)->where('name', $request->active_channel)->first()->ID;
            $channelversion = CampaignChannelVersion::all()->where('channelID', $channelID)->first();
            // var_dump(date("d-m-Y", time()));exit();
            if (!$channelversion) {
                $newVersion = new CampaignChannelVersion;
                $newVersion->versionNumber = "V 1.0";
                $newVersion->channelID = $channelID;
                $newVersion->userID = auth::user()->ID;
                $newVersion->changedDateTime = date("Y-m-d", time());
                $newVersion->save();
            } else if($ignore == 0){
                $str = array();
                $str = explode('.', $channelversion->versionNumber);
                $channelversion->versionNumber = $str[0] . "." . ($str[1] + 1);
                $channelversion->userID = auth::user()->ID;
                $channelversion->changedDateTime = date("Y-m-d H:i", time());
                $channelversion->save();
            }
            ///


        }

        $resp = array(
            'status' => 'OK',
            'msg' => $msg
        );



        return response()->json($resp);
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
}
