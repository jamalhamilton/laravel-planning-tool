<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignExport extends Model
{
    protected $table = "campaign_export";
    protected $primaryKey = "id";

    public $timestamps = false;
}
