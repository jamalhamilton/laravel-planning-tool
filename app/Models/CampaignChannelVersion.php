<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignChannelVersion extends Model
{
    //
    protected $table = "campaigns_channels_version";
    protected $primaryKey = "ID";
    public $timestamps = false;
    public function corerightuser(){
    	return $this->hasone('App\Models\CoreRightUser','ID', 'userID');
    }
}
