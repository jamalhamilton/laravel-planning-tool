<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    
    protected $table = "core_rights_users";
    protected $primaryKey = "ID";

    //protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'statusID', 'email', 'username', 'password', 'firstname', 'lastname', 'initials', 'picture', 'hasAPI', 'tokenKey'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'rememberToken',
    ];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    public function getRememberToken()
    {
        return $this->rememberToken;
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    public function setRememberToken($value)
    {
        $this->rememberToken = $value;
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    public function getRememberTokenName()
    {
        return 'rememberToken';
    }
    
}
