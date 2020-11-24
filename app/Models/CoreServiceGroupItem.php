<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoreServiceGroupItem extends Model
{
    //
    protected $table = "core_service_groups_items";
    protected $primaryKey = "ID";
    public $timestamps = false;

    public function coreservicegroup() 
    {
    	return $this->belongsTo('App\Models\CoreServiceGroup', 'groupID', 'ID');
    }

    public function campaignchannelparameters()
    {
    	return $this->hasMany('App\Models\CampaignChannelParameter', 'serviceItemID', 'ID');
    }
}
