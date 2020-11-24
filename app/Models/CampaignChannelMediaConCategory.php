<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignChannelMediaConCategory extends Model
{
    //
    protected $table = "campaign_channels_media_con_categories";
    protected $primaryKey = "ID";
    public $timestamps = false;

    public function campaignchannelmedia(){
    	return $this->hasMany('App\Models\CampaignChannelMedia','categoryID', 'ID')->orderBy('rowOrder','ASC');
    }

    public function corecampaigncategory(){
    	return $this->belongsTo('App\Models\CoreCampaignCategory','categoryID', 'ID');
    } 
      
}
