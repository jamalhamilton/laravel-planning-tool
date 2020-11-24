<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoreRightUserGroup extends Model
{
    //
    protected $table = "core_rights_usergroups";
    protected $primaryKey = "ID";
    public $timestamps = false;

    public function corerightgroup(){
    	return $this->hasOne('App\Models\CoreRightGroup', 'ID' ,'groupID');  
    }
}
