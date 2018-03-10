<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\User;
use Auth;
use Hash;

class UserSettingController extends Controller
{
  //  protected $Routes;
    protected $data;

    public function __Construct($Routes){
  //    $this->Routes = $Routes;
      $this->data['root'] = $Routes;
    }

    public function GetUserSetting(){
      return view('user_settings', $this->data);
    }


    public function UpdatePwd(Request $request){
      $this->validate($request, [
          'cr_pwd'  =>  'required|between:4,12',
          'new_pwd' =>  'required|between:4,12',
          're_pwd'  =>  'required|between:4,12|same:new_pwd',
      ]);

      $user = Auth::user();

      if (Hash::check($request->cr_pwd, $user->password)) {
        $user->password = bcrypt($request->new_pwd);

    		if($user->name == 'demo') {

            return redirect('user-settings')
              ->with([
                  'toastrmsg' => [
                    'type' => 'error',
                    'title'  =>  'User Settings',
                    'msg' =>  'In Demo Password Can Not Be Change!'
                    ],
                  ]);
    		}

        $user->save();
        return redirect('user-settings')
          ->with([
              'toastrmsg' => [
                'type' => 'success',
                'title'  =>  'User Settings',
                'msg' =>  'Password Changed'
                ],
              ]);
      } else {
        return redirect()->back()
        ->withErrors([
                  'cr_pwd' => 'Password Not Match',
              ]);
      }

    }

    public function ChangeSession(Request $request){
      Auth::user()->academic_session = $request->input('current_session');
      Auth::user()->save();
      return redirect()->back()->with([
                  'toastrmsg' => [
                    'type' => 'success',
                    'title'  =>  'User Settings',
                    'msg' =>  'Session Changed'
                    ],
                  ]);
    }

    public function SkinCfg(Request $request){
      $user = Auth::user();

        $settings = $user->settings;

      if($user->settings->skin_config->nav_collapse == 'mini-navbar'){
        $settings->skin_config->nav_collapse  = '';
      } else {
        $settings->skin_config->nav_collapse  = "mini-navbar";
      }

      $user->settings = $settings;
      $user->save();

      return response([
              'toastrmsg' => [
                'type' => 'success',
                'title'  =>  'User Settings',
                'msg' =>  'Update Skin Setting'
                ],
              ]);

    }

}
