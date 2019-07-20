<?php

namespace App\Http\Controllers\Api\TMS;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Validator;

class UserController extends Controller
{

    public $successStatus = 200;
    protected $PostLoginData, $LoginUserIDKey;


    /**
     * login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request){

//    $this->LoginUserIDKey = filter_var($request->input('userid'), FILTER_VALIDATE_EMAIL)? 'email' : 'name';
        $this->LoginUserIDKey = 'name';
        $request->merge([$this->LoginUserIDKey => $request->input('user_id')]);
        $this->PostLoginData = [$this->LoginUserIDKey => $request->input($this->LoginUserIDKey), 'password' => $request->input('password')];

        $this->ValidateLogin($request);

        if (Auth::validate($this->PostLoginData)) {
			$user = Auth::getLastAttempted();
            if (!$user->active) {
                return response()->json(['error'=>'unauthorized', 'msg' => 'You must be Active to login'], 401);
            }
            
            if($user->user_type == 'employee' || $user->user_type == 'teacher'){
                
                if (Auth::attempt($this->PostLoginData)) {
                    $user = Auth::user();
                    $token =  $user->createToken('TMS', ['tms'])->accessToken;
                    return response()->json(['User' => $user, 'token' => $token], $this->successStatus);
                }
                
            }
        }

        return response()->json(['error'=>'unauthorized', 'msg' => 'Invalid UserID OR Password'], 401);

    }


    public function Logout(Request $request){
        $this->validate($request, [
            'token' =>  'required'
        ]);
        $request->user()->token()->delete();
        return response()->json(['msg' => "Logout"]);
    }

    protected function ValidateLogin(Request $request){
        $this->validate($request, [
            $this->LoginUserIDKey => 'required|min:4|max:255',
            'password' => 'required|min:6|max:12',
            ],
            [
            $this->LoginUserIDKey.'.required' => 'UserID is Required',
            'password.required' => 'Password is Required',
            ]
        );
    }

}