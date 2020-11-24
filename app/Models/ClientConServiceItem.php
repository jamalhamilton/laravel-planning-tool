<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientConServiceItem extends Model
{
    //
    protected $table = "clients_con_services_items";
    protected $primaryKey = "ID";
    public $timestamps = false;

    public function clientconservice()
    {
    	$this->belongTo('App\Models\ClientConService', 'conServiceID', 'ID');
    }
}
