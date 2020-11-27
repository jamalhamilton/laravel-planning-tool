<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClickRate extends Model
{
    //
    protected $table = "campaign_channels_cpc";
    protected $primaryKey = "ID";
    
    public $timestamps = false;


}
