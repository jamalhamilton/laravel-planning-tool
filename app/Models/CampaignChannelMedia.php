<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignChannelMedia extends Model
{
    //
    protected $table = "campaign_channels_media";
    protected $primaryKey = "ID";
    public $timestamps = false;
    public function campaignchannelmediaconcategory(){
    	return $this->belongsTo('App\Models\CampaignChannelMediaConCategory','categoryID', 'ID');
    }
    public function campaignchannelmedianote(){
    	return $this->hasMany('App\Models\CampaignChannelMediaNote','mediaID', 'ID');
    }
    public function scoreadformat(){
    	return $this->belongsTo('App\Models\SCoreAdFormat','formatID', 'ID');
    }   
    public function coreregion(){
    	return $this->belongsTo('App\Models\CoreRegion','regionID', 'ID');
    }

    public function channeldistribution(){
        return $this->hasMany('App\Models\CampaignChannelDistribution','mediaID', 'ID');
    }
}
