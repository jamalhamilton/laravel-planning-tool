<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignChannel extends Model
{
    //
    protected $table = "campaign_channels";
    protected $primaryKey = "ID";
    public $timestamps = false;

    public function campaignchannelsversion() {
    	return $this->hasMany('App\Models\CampaignChannelVersion','channelID','ID');
    }
    
    public function campaignChannelmediaconcategory() {
    	return $this->hasMany('App\Models\CampaignChannelMediaConCategory','channelID','ID');
    }

    public function campaignchannelparameters() {
        return $this->hasMany('App\Models\CampaignChannelParameter','channelID','ID');
    }       
}
