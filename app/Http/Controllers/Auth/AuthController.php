<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Auth;
use App\User;
use Validator;
use App\Http\Controllers\Controller;
use App\Http\Requests;

use Illuminate\Foundation\Auth\ThrottlessLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;

//use Auth;

class AuthController extends Controller
{
    use AuthenticatesAndRegistersUsers, ThrottlessLogins;

    protected $redirectPath = '/planning';

    protected $loginPath = 'auth/login';

    public function __construct() 
    {
    	$this->middleware('guest', ['except' => 'getLogout']);
    }

    protected function validator(array $data)
    {
    	return Validator::make($data, [
    			'email' => 'required|email|max:255|unique:core_rights_users',
    			'password' => 'required|confirmed|min:6'
    		]);
    }

    protected function create(array $data)
    {
    	return User::create([
    			'email' => $data['email'],
    			'password' => bcrypt($data['password'])
    		]);
    }


    /**
     * Handle an authentication attempt.
     *
     * @return Response
     */
    public function authenticate() 
    {
    	if (Auth::attempt(['email' => $email, 'password' => $password])) {
    		return redirect()->intended('home');
    	}
    }
}
