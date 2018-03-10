<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Auth;

class UserController extends Controller {

  protected $PostLoginData, $LoginUserIDKey;

  Public function PostLogin(Request $request){

//    $this->LoginUserIDKey = filter_var($request->input('userid'), FILTER_VALIDATE_EMAIL)? 'email' : 'name';
    $this->LoginUserIDKey = 'name';
    $request->merge([$this->LoginUserIDKey => $request->input('userid')]);
    $this->PostLoginData = [$this->LoginUserIDKey => $request->input($this->LoginUserIDKey), 'password' => $request->input('password')];

    $this->ValidateLogin($request);

    if (Auth::validate($this->PostLoginData)) {
       $user = Auth::getLastAttempted();
       if (!$user->active) {
          return redirect()->back()->withInput()
         ->withErrors([
                   'invalid' => 'You must be Active to login',
               ]);
       }
    }


    if (Auth::attempt($this->PostLoginData)) {
//      dd(Auth::user());
/*		if($user->name == 'demo'){
			return redirect('students');
		}
*/
//		return redirect('dashboard');
		return redirect($request->input('redirect', 'dashboard'))->with([
          'toastrmsg' => [
            'type' => 'success', 
            'title'  =>  'Welcome to ALIGARH',
            'msg' =>  'School management system'
            ], 
          'script' => 'http://facebook.com/hashmanagement' 
        ]);
    }

    return redirect()->back()->withInput()
    ->withErrors([
              'invalid' => 'Invalid UserID OR Password',
          ]);
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

  public function LogOut(){
    Auth::logout();
    return redirect('login');
  }

  public function GetLogin(){
    return view('login');
  }

}
