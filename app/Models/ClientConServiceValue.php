<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientConServiceValue extends Model
{
    //
    protected $table = "clients_con_services_values";
    protected $primaryKey = "ID";
    public $timestamps = false;

    public function clientsconservice()
    {
    	return $this->hasOne('App\Models\ClientConService', 'conServiceID', 'ID');
    }
}
