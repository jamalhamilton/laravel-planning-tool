<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoreRightUser extends Model
{
    //
    protected $table = "core_rights_users";
    protected $primaryKey = "ID";
    public $timestamps = false;

    public function corerightuserstatus(){
    	return $this->hasOne('App\Models\CoreRightUserStatus', 'ID' ,'statusID');
    }

    public function corerightusergroup(){
    	return $this->belongsTo('App\Models\CoreRightUserGroup', 'ID','userID');
    }
}


