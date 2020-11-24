<?php

namespace App\Http\Controllers;

use DB;
use App\Models\CoreRightUser;
use App\Models\CoreRightUserStatus;
use App\Models\CoreRightUserGroup;
use App\Models\CoreRightGroup;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

use Auth;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $coreGroups = CoreRightGroup::all();
        $coreStatuses = CoreRightUserStatus::all();

        $data = array(
                'groups' => $coreGroups,
                'statuses' => $coreStatuses
            );

        return view('pages.user.index', ['tabIndex' => 3, 'data' => $data]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function tableDisplay()
    {
        $coreUsers = CoreRightUser::all();
        
        foreach ($coreUsers as $coreUser) {
            $id = $coreUser->ID;
            $picture = $coreUser->picture;
            $firstname = $coreUser->firstname;
            $lastname = $coreUser->lastname;
            $initial = $coreUser->initials;
            $email = $coreUser->email;
            $hasAPI = $coreUser->hasAPI;
            $tokenKey = $coreUser->tokenKey;

            if ($coreUser->corerightusergroup) {
                $group = $coreUser->corerightusergroup->corerightgroup->name;
            } else {
                $group = 'Adminitrator';
            }

            if ($coreUser->corerightuserstatus) {
                $status = $coreUser->corerightuserstatus->status;
            } else {
                $status = 'Inaktiv';
            }

            $data[] = array(
                'picture' => $picture,
                'firstname' => $firstname,
                'lastname' => $lastname,
                'initial' => $initial,
                'email' => $email,
                'tokenKey' => $tokenKey,
                'group' => $group,
                'status' => $status,
                'ID' => $id,
            );
        }

        return response()->json($data); 
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function adduser(Request $request)
    {
        $firstname = $request->firstname;
        $lastname = $request->lastname;
        $email = $request->email;

        if (CoreRightUser::where('email',$email)->first()) {
            $data = array('emailValid'=>'error');
            return response()->json($data);         
        }

        $initial = $request->initial;
        $password = $request->password;
        $hasAPI = $request->hasAPI;
        $group = $request->group;
        $status = $request->status;

        $user = new CoreRightUser;
        $user->firstname = $firstname;
        $user->lastname = $lastname;
        $user->initials = $initial;
        $user->email = $email;
        $user->password = Hash::make($request->password);
        $user->hasAPI = $hasAPI;
        $user->group_name = $group;

        if ($hasAPI == 1) {
            $str = sha1($firstname.$lastname.$email.$password);
            $user->tokenKey = substr($str, -10);
        }
        
        $user->statusID = CoreRightUserStatus::where('status', $status)->first()->ID;

        if ($request->picture) {
            $file = $request->picture;
            $destinationPath = 'uploads/user';
            $fileName = $file->getClientOriginalName();
            $fileExtension = $file->getClientOriginalExtension();

            $pos = strripos($fileName, $fileExtension);
            $onlyName = substr($fileName, 0, $pos - 1);

            $t = time();
            $logoName = $onlyName.'(GMT_'.$t.').'.$fileExtension;
            $request->file('picture')->move($destinationPath, $logoName);

            $logoUrl = '/'.$destinationPath.'/'.$logoName;

            $user->picture = $logoUrl;
        } else {
            $logoUrl = "/uploads/user/default.jpg";
            $user->picture = $logoUrl;
        }

        $user->save();

        $userID = $user->ID;
        $groupID = CoreRightGroup::where('name',$group)->first()->ID;
        $usergroup = new CoreRightUserGroup;
        $usergroup->groupID = $groupID;
        $usergroup->userID = $userID;
        $usergroup->save();

        $data = array('status'=>'success',
                      'pictureURL'=>$logoUrl,
                      'userID' => $userID, 
                      'tokenKey' => $user->tokenKey);
        
        return response()->json($data);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteUser(Request $request)
    {
        $userID = $request->userID;

        $user = CoreRightUser::where('ID',$userID)->first();

        $user->statusID = 2;
        $user->save();

        return response()->json(array('status'=>'success'));
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function displayinmodal(Request $request)
    {
        $userID = $request->userID;
        
        $user = CoreRightUser::where('ID', $userID)->first();
        
        if ($user) {
            $firstname = $user->firstname;
            $lastname = $user->lastname;
            $initial = $user->initials;
            $email = $user->email;   
            $password = $user->password;
            $avatar = $user->picture;
            $hasAPI = $user->hasAPI;
            $tokenKey = $user->tokenKey;

            if ($user->corerightusergroup) {
                $group = $user->corerightusergroup->corerightgroup->ID;
            } else {
                $group = 1;
            }

            
            if ($user->corerightuserstatus) {
                $status = $user->corerightuserstatus->ID;
            } else {
                $status = 1;
            }
            
        } else {
            $firstname = '';
            $lastname = '';
            $initial = '';
            $email = '';   
            $password = '';
            $avatar = '';
            $hasAPI = 0;
            $tokenKey = '';

            $group = 1;
            $status = 1;
        }

        $data = array(
            'firstname' => $firstname,
            'lastname' => $lastname,
            'initial' => $initial,
            'email' => $email,
            'password' => $password,
            'avatar' => $avatar,
            'hasAPI' => $hasAPI,
            'tokenKey' => $tokenKey,
            'group' => $group,
            'status' => $status,
        );

        return response()->json($data);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function edituser(Request $request)
    {
        $id = $request->ID;
        $firstname = $request->firstname;
        $lastname = $request->lastname;
        $email = $request->email;
        $initial = $request->initial;
        $password = $request->password;
        $hasAPI = $request->hasAPI;
        
        $groupID = $request->group;
        $statusID = $request->status;

        $user = CoreRightUser::where('ID',$id)->first();
        $user->firstname = $firstname;
        $user->lastname = $lastname;
        $user->initials = $initial;
        $user->email = $email;
        
        if ($user->password != $password) {
            $user->password = Hash::make($password);
        }
        
        $user->hasAPI = $hasAPI;
        $user->statusID = $statusID;

        if ($hasAPI == 1) {
            $str = sha1($firstname.$lastname.$email.$password);
            $user->tokenKey = substr($str, -10);
        } else {
            $user->tokenKey = '';
        }
                
        if ($request->picture) {
            $file = $request->picture;
            $destinationPath = 'uploads/user';
            $fileName = $file->getClientOriginalName();
            $fileExtension = $file->getClientOriginalExtension();

            $pos = strripos($fileName, $fileExtension);
            $onlyName = substr($fileName, 0, $pos - 1);

            $t = time();
            $logoName = $onlyName.'(GMT_'.$t.').'.$fileExtension;
            $request->file('picture')->move($destinationPath, $logoName);

            $logoUrl = '/'.$destinationPath.'/'.$logoName;

            $user->picture = $logoUrl;
        }

        $user->group_name = CoreRightGroup::where('ID',$groupID)->first()->name;

        $user->save();

        $usergroup = CoreRightUserGroup::where('userID',$id)->first();
        
        if ($usergroup) {
            $usergroup->groupID = $groupID;
            $usergroup->save();    
        }
        
        $data = array('status'=>'success',
                      'pictureURL'=> $user->picture, 
                      'tokenKey'=> $user->tokenKey);
        
        return response()->json($data);
    }
}
