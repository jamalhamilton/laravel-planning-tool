<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoreServiceGroup extends Model
{
    //
    protected $table = "core_service_groups";
    protected $primaryKey = "ID";
    public $timestamps = false;

    public function coreservicegroupitems() 
    {
    	return $this->hasMany('App\Models\CoreServiceGroupItem', 'groupID', 'ID');
    }
}
