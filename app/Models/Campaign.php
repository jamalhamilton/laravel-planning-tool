<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    //
    protected $table = "campaigns";
    protected $primaryKey = "ID";
    
    public $timestamps = false;

    public function campaignchannels(){
    	return $this->hasMany('App\Models\CampaignChannel','campaignID', 'ID');
    }

    public function clients(){
    	return $this->belongsTo('App\Models\Client','clientID', 'ID');
    }

    public function corecampaignstatus(){
    	return $this->hasOne('App\Models\CoreCampaignStatus','ID', 'statusID');
    }
}
