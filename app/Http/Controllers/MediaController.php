<?php

namespace App\Http\Controllers;

use App\Models\CampaignChannelMediaCPC;
use App\Models\CoreCampaignCategory;
use App\Models\ClickRate;
use App\Models\Campaign;
use App\Models\CampaignChannel;
use App\Models\CampaignChannelMediaConCategory;
use App\Models\CoreRegion;
use App\Models\CampaignChannelMedia;
use App\Models\CampaignChannelDistribution;
use App\Models\SCoreAdFormat;
use App\Models\CampaignChannelMediaNote;
use App\Models\CampaignChannelVersion;
use App\Models\CampaignChannelParameter;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

class MediaController extends Controller
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
        $campaignName = $campaign->name;
        $active_channel = $request->channel;

        $channels = CampaignChannel::where('campaignID', $campaignID)->get()->toArray();

        $hasChannel = CampaignChannel::where('campaignID' ,$campaignID)->where('name',$active_channel)->count();
        $currentCategories = array();

        if ($hasChannel !=0) {
            $currentChannel = CampaignChannel::where('campaignID' ,$campaignID)->where('name',$active_channel)->first()->ID;
            $currentCategory = CampaignChannelMediaConCategory::where('channelID',$currentChannel)->get();  
            
            if (count($currentCategory) > 0) {
                return redirect('media/fill?channel='.$active_channel.'&id='.$request->id);
            }

            foreach ($currentCategory as $ctg) {
                $currentCategories[$ctg->categoryID] = CoreCampaignCategory::where('ID',$ctg->categoryID)->where('channel_name', $active_channel)->first();
            }
            
        } else {
            return redirect('planning/overview?id='.$campaignID);
        }

        $categories = CoreCampaignCategory::where('isConstant','1')->where('channel_name', $active_channel)->get();

        $campaigns = Campaign::all();
        $campaignsName = array();
        
        foreach ($campaigns as $campaign) {
            $count = CampaignChannel::where('campaignID' ,$campaign->ID)->where('name',$active_channel)->count();
            
            if($count != 0){
                $campaignsName[] = array(
                    'id' => $campaign -> ID,
                    'name' => $campaign -> name
                );
            }
        }


        return view('pages.media.index',
                    ['tabIndex' => 1,
                     'campaignID' => $campaignID,
                     'activetab' => $active_channel,
                     'data' => $categories,
                     'campaings' => $campaignsName,
                     'campaignName' => $campaignName,
                     'currentCategories' => $currentCategories, 
                     'channels' => $channels ]);
    }

    public function saveMediaOrder(Request $request){
        $ids = $request->ids;
        $resp = [];
        foreach($ids as $k=>$id){
            if($id){
                $currentCategory = CampaignChannelMedia::where('ID',$id)->first();
                if($currentCategory) {
                    $currentCategory->rowOrder = ($k + 1);
                    $currentCategory->save();
                    $resp[$id] = ($k + 1);
                }
            }
        }

        return response()->json($resp);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function fill(Request $request)
    {
        $campaignID = $request->id;
        $campaign = Campaign::where('ID',$campaignID)->first();
        $campaignName = $campaign->name;
        $active_channel = $request->channel;

        $channels = CampaignChannel::where('campaignID', $campaignID)->get()->toArray();

        $currentChannel = CampaignChannel::where('campaignID' ,$campaignID)->where('name',$active_channel)->first();
        $channelID = 0;

        if ($currentChannel) {
            $channelID = $currentChannel->ID;
        } else {
            return redirect('planning/overview?id='.$campaignID);
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
                $clickrate = $category->clickrate;
                $medias = $category->campaignchannelmedia;

                $mediaData = array();

                $index = 0;
                foreach ($medias as $media) {
                    $notes = array();

                    foreach ($media->campaignchannelmedianote as $note) {
                        $notes[$note->colOrder] = $note->note;
                    }

//                    $grossCHF = ($media->is_cpc)? $media->adPressureValue * $media->tkpGrossCHF : $media->grossCHF;
                    $grossCHF =  $media->grossCHF;

                    $mediaData[$index] = array(
                        'id' => $media->ID,
                        /* 'region' => $media->coreregion->name,*/
                        'placement' => $media->placing,
                        'details' => $media->details,
                        'adPrint' => number_format($media->adPressureValue, 0, '.', '\''),
                        /*'format' => $media->scoreadformat->name,*/
                        'tkpGrossCHF' => number_format($media->tkpGrossCHF, 2, '.', '\''),
                        'grossCHF' => number_format($grossCHF, 2, '.', '\''),
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
                        'is_cpc'=>$media->is_cpc,
                        'ad_impressions'=>$media->ad_impressions
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
                    'clickrate' => $clickrate,
                    'media' => $mediaData,
                );
            }
        }
        $clickrate = ClickRate::all();
        $clickrateArr = [];
        foreach ($clickrate as $c){
            $clickrateArr[$c->name] = $c->value;
        }

        return view('pages.media.'.$active_channel.'.fill',
                    ['tabIndex' => 1,
                     'activetab' => $active_channel,
                     'data' => $data,
                     'clickrateArr'=>$clickrateArr,
                     'campaignName' => $campaignName,
                     'calRegions' => $calRegions,
                     'constantCategories' => $constantCategories,
                     'currentCategories' => $currentCategories,
                     'campaignID' => $request->id,
                     'regions' => $regions,
                     'channels' => $channels]);
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function autocomplete(Request $request)
    {

        $formats = array();

        foreach (SCoreAdFormat::where("channel_name", $request->active_channel)->get() as $format) {
           array_push($formats, $format->name);    
        }
        $resp = array(
            'status' => 'OK',
            'formats' =>$formats
            );
        return response()->json($resp);
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function insertNote(Request $request){
        $hasNote = CampaignChannelMediaNote::where('mediaID',$request->mediaID)->where('colOrder',$request->colOrder)->count(); 
        if ($hasNote == 0) {
            $note = new CampaignChannelMediaNote;
            $note->note = $request->value;
            $note->colOrder = $request->colOrder;
            $note->mediaID = $request->mediaID;
        } else {
            $note = CampaignChannelMediaNote::where('mediaID',$request->mediaID)->where('colOrder',$request->colOrder)->first(); 
            $note->note = $request->value;        
        }

        $msg = ($note->save())? "Edit Successed!" : "Edit Failed!";

        $this->plusVersionNumber($request->campaignID, $request->active_channel);

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
    public function deleteNote(Request $request)
    {    
        $note = CampaignChannelMediaNote::where('mediaID',$request->mediaID)->where('colOrder',$request->colOrder)->first();        
        if($note != NULL){
            $msg = ($note->delete()) ? "Delete Successed!" : "Delete Failed!";   
        }else{
            $msg = "There is no note!";
        }
        $this->plusVersionNumber($request->campaignID, $request->active_channel);
        $resp = array(
            'status' => 'OK',
            'msg' => $msg
            );

        return response()->json($resp);
    }

    /**
     * update CPC
     *
     * @return \Illuminate\Http\Response
     */
    public function insertCPC(Request $request){
        $media = CampaignChannelMedia::where('ID',$request->mediaID)->first();
        $clickrate = $request->clickrate;

        $msg = "Invalid";$cpc = -1;
        if(!empty($media)){
            $media->is_cpc = 1 - $media->is_cpc;
            $cpc = $media->is_cpc;
            //update cpc
            if($cpc){
                $media->grossCHF = $media->adPressureValue * $media->tkpGrossCHF;
                if($clickrate){
                    $media->ad_impressions = $media->adPressureValue/$clickrate*100;
                }else{
                    $media->ad_impressions = 0;
                }

            }else{
                $media->grossCHF = $media->adPressureValue / 1000 * $media->tkpGrossCHF;
                $media->ad_impressions = 0;
            }

            $msg = ($media->save())? "CPC has updated!" : "CPC Failed!";
        }

        $this->plusVersionNumber($request->campaignID, $request->active_channel);

        $resp = array(
            'status' => 'OK',
            'msg' => $msg,
            'cpc'=>$cpc,
            'media'=>$media
        );

        return response()->json($resp);

    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function insertCtNote(Request $request){
        $category = CampaignChannelMediaConCategory::where('ID',$request->categoryID)->first();
        $category->categoryNote = $request->value;
        $msg = ($category->save()) ? "Edit Successed!" : "Edit Failed!";

        $this->plusVersionNumber($request->campaignID, $request->active_channel);
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
    public function deleteCtNote(Request $request)
    {    
        $category = CampaignChannelMediaConCategory::where('ID',$request->categoryID)->first();
        $category->categoryNote = "";
        $msg = ($category->save()) ? "Edit Successed!" : "Edit Failed!";

        $this->plusVersionNumber($request->campaignID, $request->active_channel);
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
    public function deleteLine(Request $request)
    {
        foreach ($request->selectedID as $mediaID) {
            $media = CampaignChannelMedia::where('ID',$mediaID)->first();        
            $msg = ($media->delete()) ? "Delete Successed!" : "Delete Failed!";     
        }

        $this->plusVersionNumber($request->campaignID, $request->active_channel);

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
    public function insertLine(Request $request)
    {
        $active_channel = $request->active_channel;

        $cpc = intval($request->cpc);
        $clickrate = floatval($request->clickrate);

        if($active_channel == 'online' || $active_channel == 'plakat'
            || $active_channel == 'kino' ||  $active_channel == 'radio' ||  $active_channel == 'ambient' ) {
            $fieldname = array('placing',
                'details',
                'regionID',
                'formatValue',
                'adPressureValue',
                'tkpGrossCHF',
                'grossCHF',
                'discountPersentual',
                'netCHF',
                'bkPersentual',
                'nnCHF',
                'tkpNNCHF');
        }else if( $active_channel == 'tv') {
            $fieldname = array('placing',
                'details',
                'regionID',
                'formatValue',
                'adPressureValue',
                'kontaktsumme',
                'grps',
                'tkpGrossCHF',
                'grossCHF',
                'discountPersentual',
                'netCHF',
                'bkPersentual',
                'nnCHF',
                'tkpNNCHF');
        }else if($active_channel == 'print') {
            $fieldname = array('placing',
                'details',
                'regionID',
                'formatValue',
                'adPressureValue',
                'grossCHF',
                'discountPersentual',
                'netCHF',
                'bkPersentual',
                'nnCHF');
        }

        $media = new CampaignChannelMedia; 
        $media->categoryID = $request->categoryID;
        $media->regionID = -1;
       // $media->formatID = -1;
        $media->formatValue = "";
        $data = $request->data;
        if($data){

            foreach ($fieldname as $k=>$key){
                if($k == 2){
                    if($data[2]) {
                        $regions = explode(', ', $data[2]);
                        $regions_id = [];
                        foreach ($regions as $region_name) {
                            $tmpregionID = CoreRegion::where('name', $region_name)->first();
                            if($tmpregionID){
                                $regions_id[] = $tmpregionID->ID;
                            }
                        }
                        $media->regionID = implode(', ', $regions_id);
                    }
                }else{
                    if($k>3){
                        $media->{$key} = floatval(str_replace('\'','',$data[$k]));
                    }else{
                        $media->{$key} = ($data[$k])?$data[$k]:"";
                    }

                }
//                dd($media);
            }

        }

        if($cpc){
            $media->is_cpc = 1;
            $media->grossCHF = $media->adPressureValue * $media->tkpGrossCHF;
            $media->ad_impressions = $media->adPressureValue/$clickrate*100;

        }else{
            $media->grossCHF = $media->adPressureValue / 1000 * $media->tkpGrossCHF;
        }
        $msg = ($media->save()) ? "Insert Successed!" : "Insert Failed!";

        /////////////// Ads update ///////////

        $media = CampaignChannelMedia::where('ID',$media->ID)->first();
        $onlineChannel = CampaignChannel::where('campaignID', $request->campaignID)->where('name', $request->active_channel)->first();

        $startDate = $onlineChannel->startDate;
        $endDate = $onlineChannel->endDate;
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

        $dists = CampaignChannelDistribution::where('mediaID', $media->ID)->get();

        if($active_channel == 'online'
            || $active_channel == 'tv' || $active_channel == 'ambient') {   // just remove all distribution when it is online.
            foreach ($dists as $dist) {
                $dist->delete();
            }

            for ($i = $startWeek; $i <= ($endWeek - $hasExtra); $i++) {
                $dist = new CampaignChannelDistribution;
                $dist->mediaID = $media->ID;

                if($active_channel == 'tv')
                    $dist->distributionCount =  intval($media->grps / ($endWeek - $startWeek + 1 - $hasExtra));
                else
                    $dist->distributionCount =  intval($media->adPressureValue / ($endWeek - $startWeek + 1 - $hasExtra));
                $dist->weekNumber = $i;
                $dist->save();
            }
        }

        if(($active_channel == 'print' || $active_channel == 'radio' || $active_channel == 'plakat' || $active_channel == 'kino') && sizeof($dists) == 0) {  // insert new black cells when there is no data as default (ads)

            for ($i = $startWeek; $i <= ($endWeek - $hasExtra); $i++) {
                $dist = new CampaignChannelDistribution;
                $dist->mediaID = $media->ID;
                $str = implode('', explode('\'', $request->value));

                $dist->distributionCount = "";

                $dist->weekNumber = $i;
                $dist->save();
            }
        }

        /////////
        $this->plusVersionNumber($request->campaignID, $request->active_channel);

        $resp = array(
            'status' => 'OK',
            'msg' => $msg,
            'id' => $media->ID,
            'media'=>$media
            );

        return response()->json($resp);

    }   

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function editCol(Request $request)
    {

        $media = CampaignChannelMedia::where('ID',$request->mediaID)->first();
        $onlineChannel = CampaignChannel::where('campaignID', $request->campaignID)->where('name', $request->active_channel)->first();
        $clickrate = $request->clickrate;

        $fieldname = array();

        $active_channel = $request->active_channel;
        if($active_channel == 'online' || $active_channel == 'plakat'
            || $active_channel == 'kino' ||  $active_channel == 'radio' ||  $active_channel == 'ambient' ) {
            $fieldname = array('placing', 'details', 'regionID', 'formatValue', 'adPressureValue', 'tkpGrossCHF', 'grossCHF', 'discountPersentual', 'netCHF', 'bkPersentual', 'nnCHF', 'tkpNNCHF', 'ID', 'categoryID', 'rowOrder');
        }
        if( $active_channel == 'tv') {
            $fieldname = array('placing', 'details', 'regionID', 'formatValue', 'adPressureValue', 'kontaktsumme', 'grps', 'tkpGrossCHF', 'grossCHF', 'discountPersentual', 'netCHF', 'bkPersentual', 'nnCHF', 'tkpNNCHF', 'ID', 'categoryID', 'rowOrder');
        }
        if($active_channel == 'print') {
            $fieldname = array('placing', 'details', 'regionID', 'formatValue', 'adPressureValue', 'grossCHF', 'discountPersentual', 'netCHF', 'bkPersentual', 'nnCHF', 'ID', 'categoryID', 'rowOrder');
        }

        if($request->colIndex == 0 || $request->colIndex == 1 || $request->colIndex == 3){
            $request->value = str_replace("\n", "<br>", $request->value);
        }
        if ($request->colIndex == 3) {

            if(isset($request->value))
                $media->formatValue = $request->value;
            else
                $media->formatValue = "";
            $msg = ($media->save()) ? "Edit Successed!" : "Edit Failed!";            
        } else {
            // var_dump($fieldname[$request->colIndex]);exit();
            if(isset($request->value)) {
                $media[$fieldname[$request->colIndex]] = implode('', explode('\'', $request->value));
                $msg = ($media->save()) ? "Edit Successed!" : "Edit Failed!";
            }
            else{
                $msg = "Edit Successed!";
            }
        }

        if ($request->colIndex == 2){

//            $regionID = CoreRegion::where('name',$request->value)->first()->ID;
//            $media->regionID = $regionID;
//            $media->save();

            $regions = explode(', ', $request->value);
            $regions_id = [];
            foreach ($regions as $region_name) {
                $tmpRegion = CoreRegion::where('name', $region_name)->first();
                if($tmpRegion){
                    $regions_id[] = $tmpRegion->ID;
                }

            }
            $media->regionID = implode(', ', $regions_id);
            $media->save();

        }

        if (($request->colIndex == 4) || ($request->colIndex == 6 && $active_channel == 'tv')) {

                $startDate = $onlineChannel->startDate;
                $endDate = $onlineChannel->endDate;
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

                $dists = CampaignChannelDistribution::where('mediaID', $request->mediaID)->get();

                if($active_channel == 'online'
                    || $active_channel == 'tv' || $active_channel == 'ambient') {   // just remove all distribution when it is online.
                    foreach ($dists as $dist) {
                        $dist->delete();
                    }

                    for ($i = $startWeek; $i <= ($endWeek - $hasExtra); $i++) {
                        $dist = new CampaignChannelDistribution;
                        $dist->mediaID = $request->mediaID;

                        if($active_channel == 'tv')
                            $dist->distributionCount =  intval($media->grps / ($endWeek - $startWeek + 1 - $hasExtra));
                        else
                            $dist->distributionCount =  intval($media->adPressureValue / ($endWeek - $startWeek + 1 - $hasExtra));
                        $dist->weekNumber = $i;
                        $dist->save();
                    }
                }
                if(($active_channel == 'print' || $active_channel == 'radio' || $active_channel == 'plakat' || $active_channel == 'kino') && sizeof($dists) == 0) {  // insert new black cells when there is no data as default (ads)

                    for ($i = $startWeek; $i <= ($endWeek - $hasExtra); $i++) {
                        $dist = new CampaignChannelDistribution;
                        $dist->mediaID = $request->mediaID;
                        $str = implode('', explode('\'', $request->value));

                        $dist->distributionCount = "";

                        $dist->weekNumber = $i;
                        $dist->save();
                    }
                }


            // update for planning overview
                $adpresssum = $request->adpresssum;  // this is for Traffic-Kosten
                if($active_channel == 'online' ){
                    $campaign = Campaign::where('ID', $request->campaignID)->first();
                    $selectRates = $this->getRatesKosten_Honorar($campaign->clientID);
                    $subTotal = $adpresssum * $selectRates['Technische_Kosten'] / 1000;

                    // serviceItemID =2 : Traffic-Kosten constant value.
                    $channelParam = CampaignChannelParameter::where('channelID', $onlineChannel->ID)->where('serviceItemID', '2')->first();
                    if(isset($channelParam)) {
                        $channelParam->value =  round($adpresssum, 2);
                        $channelParam->calcValue = round($subTotal, 2);

                        $channelParam->save();
                    }
                    else{
                        $channelParam = new CampaignChannelParameter;
                        $channelParam->value =  round($adpresssum, 2);
                        $channelParam->calcValue = round($subTotal, 2);
                        $channelParam->channelID = $onlineChannel->ID;
                        $channelParam->serviceItemID = '2'; // Technische Kosten
                        $channelParam->name = 'Traffic-Kosten'; // Technische Kosten

                        //conServiceID = 7: this is for con item " Traffic-Kosten"
                        $query = "select * from clients_con_services_values where clientID = ? and conServiceID = 7" ;
                        $result = DB::select($query, [$campaign->clientID]);

                        $channelParam->clientServiceItemID = $result[0]->ID;  // Traffic-Kosten

                        $channelParam->save();
                    }

                }


            }

        $mediaData = array();
        
        if ($request->type == "free-input" && $request->colIndex > 2) {
            $prevNNCHF  = $media->nnCHF;

            $campaign = Campaign::where('ID', $request->campaignID)->first();
            $selectRates = $this->getRatesKosten_Honorar($campaign->clientID);

            if($active_channel == 'online' || $active_channel == 'ambient'  ) {
                //dd($request->all());
                if (!empty($media->adPressureValue)) {
                    if ($media->tkpGrossCHF != 0 && $media->grossCHF != 0 || $request->colIndex == 5){

                        if($media->is_cpc){
                            $media->grossCHF = $media->adPressureValue * $media->tkpGrossCHF;
                            $media->ad_impressions = $media->adPressureValue/$clickrate*100;
                        }else{
                            $media->grossCHF = $media->adPressureValue / 1000 * $media->tkpGrossCHF;
                            $media->ad_impressions = 0;
                        }
                    }


                    if ($media->tkpGrossCHF == 0 && $request->colIndex == 6){
                        $media->tkpGrossCHF = 1000 * $media->grossCHF /  $media->adPressureValue;
                    }
                }

                $media->netCHF = $media->grossCHF * ((100 - $media->discountPersentual) / 100);
                $media->nnCHF = $media->netCHF * ((100 - $media->bkPersentual) / 100);
                if ($media->adPressureValue == 0) {
                    $media->tkpNNCHF = 0;
                } else {
                    $media->tkpNNCHF = ($media->nnCHF / $media->adPressureValue) * 1000;
                }

                $media->save();

            }

            if( $active_channel == 'radio' ) {
                if (!empty($media->formatValue) && !empty($media->adPressureValue) && $media->adPressureValue != 0 && $media->formatValue != 0) {
                    if (  $request->colIndex == 3 || $request->colIndex == 4 ||  $request->colIndex == 6)
                        $media->tkpGrossCHF= $media->grossCHF  / ($media->formatValue * $media->adPressureValue);
                }

                $media->netCHF = $media->grossCHF * ((100 - $media->discountPersentual) / 100);
                $media->nnCHF = $media->netCHF * ((100 - $media->bkPersentual) / 100);

                if (!empty($media->formatValue) && !empty($media->tkpGrossCHF) && $media->tkpGrossCHF != 0 && $media->formatValue != 0) {
                    $media->tkpNNCHF = $media->nnCHF / ($media->formatValue * $media->adPressureValue);
                } else {
                    $media->tkpNNCHF = 0;
                }

                $media->save();
            }

            if( $active_channel == 'plakat' ) {
                if (!empty($media->adPressureValue)) {
                    if ( $request->colIndex == 4 || $request->colIndex == 6 )
                        $media->tkpGrossCHF = $media->grossCHF/ $media->adPressureValue  ;
                }

                $media->netCHF = $media->grossCHF * ((100 - $media->discountPersentual) / 100);
                $media->nnCHF = $media->netCHF * ((100 - $media->bkPersentual) / 100);
                if ($media->adPressureValue == 0) {
                    $media->tkpNNCHF = 0;
                } else {
                    $media->tkpNNCHF = $media->nnCHF / $media->adPressureValue;
                }

                $media->save();
            }

            if( $active_channel == 'tv') {
                if (!empty($media->adPressureValue)) {
                    if ( $request->colIndex == 4 || $request->colIndex == 7 )
                        $media->tkpGrossCHF = $media->grossCHF/ $media->adPressureValue  ;
                }

                $media->netCHF = $media->grossCHF * ((100 - $media->discountPersentual) / 100);
                $media->nnCHF = $media->netCHF * ((100 - $media->bkPersentual) / 100);
                if ($media->adPressureValue == 0) {
                    $media->tkpNNCHF = 0;
                } else {
                    $media->tkpNNCHF = $media->nnCHF / $media->adPressureValue;
                }

                $media->save();
            }

            if($active_channel == 'print') {

                $media->netCHF = $media->grossCHF * ((100 - $media->discountPersentual) / 100);
                $media->nnCHF = $media->netCHF * ((100 - $media->bkPersentual) / 100);
                $media->save();
            }

            if($active_channel == 'kino'){
                if (!empty($media->adPressureValue)) {
                    if ($media->adPressureValue != 0 && ($request->colIndex == 4 || $request->colIndex == 6))
                        $media->tkpGrossCHF= number_format(($media->grossCHF /$media->adPressureValue) *1000, 2, '.', '\'');
                }

                $media->netCHF = $media->grossCHF * ((100 - $media->discountPersentual) / 100);
                $media->nnCHF = $media->netCHF * ((100 - $media->bkPersentual) / 100);
                if ($media->adPressureValue == 0) {
                    $media->tkpNNCHF = 0;
                } else {
                    $media->tkpNNCHF = ($media->nnCHF / $media->adPressureValue) * 1000;
                }

                $media->save();
            }

            ///// update Honorar auf Media N/N constant value for planning overview//////
            $nnsum = $request->nnsum + ($media->nnCHF - $prevNNCHF  );
            $subTotal = $nnsum * $selectRates['Media_Honorar']/100;

            // serviceItemID =3 : Media-Honorar constant value.
            $channelParam = CampaignChannelParameter::where('channelID', $onlineChannel->ID)->where('serviceItemID', '3')->first();
            if(isset($channelParam)) {
                $channelParam->value =  round($nnsum, 2);
                $channelParam->calcValue = round($subTotal, 2);

                $channelParam->save();
            }
            else{
                $channelParam = new CampaignChannelParameter;
                $channelParam->value =  round($nnsum, 2);
                $channelParam->calcValue = round($subTotal, 2);
                $channelParam->channelID = $onlineChannel->ID;
                $channelParam->serviceItemID = '3'; // Media-Honorar
                $channelParam->name = 'Honorar auf Media N/N';

                //conServiceID = 8: this is for con item " Honorar auf Media N/N"
                $query = "select * from clients_con_services_values where clientID = ? and conServiceID = 8" ;
                $result = DB::select($query, [$campaign->clientID]);

                $channelParam->clientServiceItemID = $result[0]->ID;  // Traffic-Kosten

                $channelParam->save();
            }

            $mediaData = array(
                'adPressureValue' =>$media->adPressureValue,
                'grossCHF' =>$media->grossCHF,
                'netCHF' =>$media->netCHF,
                'nnCHF' => $media->nnCHF,
                'tkpNNCHF' => $media->tkpNNCHF,
                'tkpGrossCHF' => $media->tkpGrossCHF,
                'colIndex' => $request->colIndex,
                );
        }

        $this->plusVersionNumber($request->campaignID, $active_channel);

        $resp = array(
            'status' => 'OK',
            'msg' => $msg,
            'mediaData' => $mediaData,
            'item'=>$media
            );

        return response()->json($resp);

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function editCategory(Request $request)
    {
        $category = CampaignChannelMediaConCategory::where('ID',$request->categoryID)->first();
        $change_rate = 0;
        
        if ($request->checkboxInfo) {
            $category->categoryNote = $request->addInfo;
        }

        $aryCPC = array();
        $clickrate =  $request->clickrate;

        if($category->clickrate !=  $clickrate){
            $change_rate = 1;
            $category->clickrate = $clickrate;
            //todo
            //calc cpc
            $medias = $category->campaignchannelmedia;
            foreach ($medias as $k=>$m){
                if($m->is_cpc){
                    $m->ad_impressions = $m->adPressureValue/$clickrate*100;
                    $m->save();
                    $aryCPC[] = $m;
                }
            }

        }

        $msg = ($category->save()) ? "Edit Successed!" : "Edit Failed!";

        $category = CoreCampaignCategory::where('ID',$category->categoryID)->where('channel_name',$request->active_channel)->first();
        $category->name = $request->categoryName;

        $msg = ($category->save()) ? "Edit Successed!" : "Edit Failed!";

        $this->plusVersionNumber($request->campaignID, $request->active_channel);


        $resp = array(
            'status' => 'OK',
            'msg' => $msg,
            'change_rate' => $change_rate,
            'clickrate'=>$request->clickrate,
            'cpc_items'=>$aryCPC
            );
        return response()->json($resp);

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function addCategory(Request $request)
    {
        $currentChannel = CampaignChannel::where('campaignID' ,$request->campaignID)->where('name', $request->active_channel)->first()->ID;
        $currentCategories = CampaignChannelMediaConCategory::where('channelID',$currentChannel)->get();
        
        $newctgIDs = array();
        $newctgNames = array();
        $isConstant = array();
        $newMediaID = array();
        $deletedID = array();

        foreach ($currentCategories as $category) {
            $isExist = false;
            
            foreach ($request->categories as $categoryName) {            
                if ($category->corecampaigncategory->name == $categoryName) {
                    $isExist = true;
                }
            }
            
            if (!$isExist) {
                $conID = $category->ID;
                $coreID = $category->categoryID;
                $medias = CampaignChannelMedia::where('categoryID',$coreID)->get();
                
                foreach ($medias as $media) {
                    $media->delete();
                }

                $deletedID[] = $conID;
                if ($category->corecampaigncategory->isConstant == 0) {
                    $category->corecampaigncategory->delete();
                }

                $category->delete();
            }
        }

        foreach ($request->categories as $categoryName) {
            $isExist = false;
            
            foreach ($currentCategories as $category) {
                
                if ($category->corecampaigncategory->name == $categoryName) {
                    $isExist = true;
                    $msg = "This category name is already exist!";
                }
            
            }

            if (!$isExist) {
                $isConstantCtg = CoreCampaignCategory::where('name',$categoryName)->where('channel_name',$request->active_channel)->where('isConstant',1)->first();
                
                if ($isConstantCtg == NULL) {
                    $category = new CoreCampaignCategory;
                    $category->name = $categoryName;
                    $category->isConstant = 0;
                    $category->channel_name = $request->active_channel;
                    $msg = ($category->save()) ? "Add Successed!" : "Add Failed!";
                    $categoryID = $category->ID;
                } else {
                    $categoryID = $isConstantCtg->ID;
                }
               
                $category = new CampaignChannelMediaConCategory;
                $channelID = CampaignChannel::where('campaignID',$request->campaignID)->where('name',$request->active_channel)->first()->ID;
                $category->channelID = $channelID;
                $category->categoryID = $categoryID;
                $msg = ($category->save()) ? "Add Successed!" : "Add Failed!";
                $newctgIDs[] = $category->ID;

                $media = new CampaignChannelMedia; 
                $media->categoryID = $category->ID;
                $media->regionID = 1;
              //  $media->formatID = 1;
                $media->formatValue = "";
                $media->save();

                $newctgNames[] = $categoryName;
                $newMediaID[] = $media->ID;
                $isConstant[] = ($isConstantCtg) ? 1 : 0;
            }
        }

        $this->plusVersionNumber($request->campaignID, $request->active_channel);

        $resp = array(
                'status' => 'OK',
                'msg' => $msg,
                'ctgID' => $newctgIDs,
                'deletedID' => $deletedID,
                'newMediaID' => $newMediaID,
                'ctgName' => $newctgNames,
                'isConstant' => $isConstant
            );

        return response()->json($resp);

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteCategory(Request $request)
    {
        $currentChannel = CampaignChannel::where('campaignID' ,$request->campaignID)->where('name',$request->active_channel)->first()->ID;
        $category = CampaignChannelMediaConCategory::where('channelID',$currentChannel)->where('ID',$request->categoryID)->first();
        $categoryID = $category->categoryID;
        $msg = ($category->delete())? "Delete Successed!" : "Delete Failed!";
        $category = CoreCampaignCategory::where('ID',$categoryID)->where('channel_name',$request->active_channel)->first();

        if ($category->isConstant == 0) {
            $msg = ($category->delete())? "Delete Successed!" : "Delete Failed!";        
        }

        $this->plusVersionNumber($request->campaignID, $request->active_channel);

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
        public function duplicateCategory(Request $request)
    {
        $channelID = Campaign::where('name',$request->campaignName)->first()->campaignchannels->where('name',$request->active_channel)->first()->ID;
        $originCategories = CampaignChannelMediaConCategory::where('channelID',$channelID)->get();
        if($originCategories->count() == 0){
            $msg ="There is no categories in this channel!";
        }
        $active_channel = $request->active_channel;
        $channel = CampaignChannel::where('campaignID',$request->campaignChannel)->where('name',$request->active_channel)->first();
        $currentChannelID = $channel->ID;
        $startDate = $channel->startDate;
        $endDate = $channel->endDate;

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

        foreach ($originCategories as $category) {
            $newCategory = new CampaignChannelMediaConCategory;
            $newCategory->channelID = $currentChannelID;
            $newCategory->categoryID = $category->categoryID;
            $newCategory->categoryNote = $category->categoryNote;
            $msg = ($newCategory->save()) ? "Edit Successed!" : "Edit Failed!";        
            $originMedias = CampaignChannelMedia::where('categoryID',$category->ID)->orderBy('rowOrder', 'ASC')->get();
            
            foreach ($originMedias as $media) {
                $newMedia = new CampaignChannelMedia;
                $newMedia->categoryID = $newCategory->ID;
                $newMedia->regionID = $media->regionID;
             //   $newMedia->formatID = $media->formatID;
                $newMedia->formatValue = $media->formatValue;
                $newMedia->placing = $media->placing;
                $newMedia->details = $media->details;
                $newMedia->adPressureValue = $media->adPressureValue;
                $newMedia->tkpGrossCHF = $media->tkpGrossCHF;
                $newMedia->grossCHF = $media->grossCHF;
                $newMedia->discountPersentual = $media->discountPersentual;
                $newMedia->netCHF = $media->netCHF;
                $newMedia->bkPersentual = $media->bkPersentual;
                $newMedia->nnCHF = $media->nnCHF;
                $newMedia->tkpNNCHF = $media->tkpNNCHF;
                $newMedia->rowOrder = $media->rowOrder;
                $newMedia->save();


                if($active_channel == 'ambient' || $active_channel == 'online'  || $active_channel == 'tv') {
                    if($active_channel == 'tv')
                        $distributionCount =  intval($media->grps / ($endWeek - $startWeek + 1 - $hasExtra));
                    else
                        $distributionCount =  intval($media->adPressureValue / ($endWeek - $startWeek + 1 - $hasExtra));

                    for ($i = $startWeek; $i <= ($endWeek - $hasExtra); $i++) {

                        $dist = new CampaignChannelDistribution;
                        $dist->mediaID = $newMedia->ID;

                        $dist->distributionCount =  $distributionCount;

                        $dist->weekNumber = $i;
                        $dist->save();
                    }
                }
                if( $active_channel == 'print'
                    || $active_channel == 'plakat' || $active_channel == 'kino' || $active_channel == 'radio' ) {

                    for ($i = $startWeek; $i <= ($endWeek - $hasExtra); $i++) {

                        $dist = new CampaignChannelDistribution;
                        $dist->mediaID = $newMedia->ID;
                        $dist->distributionCount =  "";

                        $dist->weekNumber = $i;
                        $dist->save();
                    }
                }

            }

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
    public function getCategories(Request $request)
    {
        $currentChannel = CampaignChannel::where('campaignID' ,$request->campaignID)->where('name',$request->active_channel)->first();
        $channelID = 0;

        if ($currentChannel == NULL) {
            $newChannel = new CampaignChannel;
            $newChannel->campaignID = $request->id;
            $newChannel->name = $request->active_channel;
            $newChannel->save();
            $channelID = $newChannel->ID;
        } else {
            $channelID = $currentChannel->ID;
        }

        $currentCategory = CampaignChannelMediaConCategory::where('channelID',$channelID)->get();  
        $currentCategories = array();
        
        foreach ($currentCategory as $ctg) {
            $currentCategories[$ctg->ID] = CoreCampaignCategory::where('ID',$ctg->categoryID)->where('channel_name',$request->active_channel)->first()->name;
        }

        return response()->json($currentCategories);
    }
    
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function completed(Request $request)
    {
        $campaignID = $request->id;
        $channels = CampaignChannel::where('campaignID', $campaignID)->get()->toArray();

        return view('pages.media.online.completed', ['tabIndex' => 1, 'channels' => $channels]);
    }

    public function plusVersionNumber($campaignID, $active_channel){
        // plus version number
        $channelID = CampaignChannel::where('campaignID',$campaignID)->where('name',$active_channel)->first()->ID;
        $channelversion = CampaignChannelVersion::all()->where('channelID',$channelID)->first();
        if (!$channelversion) {
            $newVersion = new CampaignChannelVersion;
            $newVersion->versionNumber = "V 1.0";
            $newVersion->channelID = $channelID;
            $newVersion->userID = auth::user()->ID;
            $newVersion->changedDateTime = date("Y-m-d", time());
            $newVersion->save();
        } else {
            $str = array();
            $str = explode('.', $channelversion->versionNumber);
            $channelversion->versionNumber = $str[0].".".($str[1]+1);
            $channelversion->userID = auth::user()->ID;
            $channelversion->changedDateTime = date("Y-m-d H:i", time());
            $channelversion->save();
        }
        ///
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
}
