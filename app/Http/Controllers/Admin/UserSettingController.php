<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Auth;
use Hash;
use App\Http\Controllers\Controller;

class UserSettingController extends Controller
{

    public function GetUserSetting(){
      return view('admin.user_settings');
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

      if(in_array($request->input('current_session'), Auth::user()->getprivileges->allow_session)){
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

      return redirect()->back()->with([
                  'toastrmsg' => [
                    'type' => 'error',
                    'title'  =>  'User Settings',
                    'msg' =>  'Session Not Allowed'
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
