<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignChannelParameter extends Model
{
    //
    protected $table = "campaign_channels_parameters";
    protected $primaryKey = "ID";
    public $timestamps = false;

    public function channel() {
    	$this->belongsTo('App\Models\CampaignChannel', 'channelID', 'ID');
    }
}
