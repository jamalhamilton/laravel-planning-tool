<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoreCampaignCategory extends Model
{
    //
    protected $table = "core_campaign_categories";
    protected $primaryKey = "ID";
    public $timestamps = false;

    protected $fillable = ['name'];
    
    public function campaignChannelmediaconcategory(){
    	return $this->hasMany('App\Models\CampaignChannelMediaConCategory','categoryID', 'ID');
    }
}
