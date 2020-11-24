<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientConService extends Model
{
    //
    protected $table = "clients_con_services";
    protected $primaryKey = "ID";
    public $timestamps = false;

    public function scopeOfClient($query, $clientID)
    {
    	return $query->where('isConstant', 1)->orWhere('clientID', $clientID);
    }
}
