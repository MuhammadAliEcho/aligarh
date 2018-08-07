<?php

namespace App\Http\Controllers\Admin;

use Yajra\Datatables\Facades\Datatables;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\UserPrivilege;
use App\AdminContent;
use App\Employee;
use App\Teacher;
use App\User;
use Auth;

class UsersController extends Controller
{

    protected $data, $User, $Request;

    public function __Construct($Routes, Request $Request){
      $this->data['root'] = $Routes;
      $this->Request = $Request;
    }

    protected function PostValidate(){
      $this->validate($this->Request, [
          'name'  =>  'sometimes|required|unique:users,name'. (($this->data['root']['option'] !== '')? ','.$this->User->id : ''),
          'email' =>  'sometimes|required|email|unique:users,email'. (($this->data['root']['option'] !== '')? ','.$this->User->id : ''),
          'status'  =>  'required',
          'password' =>  'sometimes|nullable|between:6,12',
          're_password'  =>  'sometimes|nullable|between:6,12|same:password',
      ]);
    }

    public function GetUsers(){

      if ($this->Request->ajax()) {
        return Datatables::eloquent(User::select('id', 'name', 'email', 'role', 'foreign_id', 'user_type', 'active'))->make(true);
      }

      $this->Content();
      return view('admin.users', $this->data);
    }

    public function EditUser(){
      $this->Content();
      $this->data['user'] = User::findOrfail($this->data['root']['option']);
      return view('admin.edit_user', $this->data);
    }

    public function PostEditUser(Request $request){

      $this->Request = $request;
      $this->User = User::findOrfail($this->data['root']['option']);
        if($this->User->created_by == 0 && Auth::user()->id != 1){
        return redirect('users')->with([
        'toastrmsg' => [
          'type' => 'warning', 
          'title'  =>  'Users Registration',
          'msg' =>  '"'.$this->User->name.'" User Can\'t be Editable'
          ]
        ]);
        }

      $this->PostValidate();
      $this->SetAttributes();
      if (Auth::user()->getprivileges->privileges->{$this->data['root']['content']['id']}->editpwd && !empty($this->Request->input('password'))) {
        $this->User->password = bcrypt($this->Request->input('password'));
      }

      $this->User->updated_by  = Auth::user()->id;
      $this->User->save();

      $this->SetPrivileges();

      return redirect('users')->with([
        'toastrmsg' => [
          'type' => 'success', 
          'title'  =>  'Users Registration',
          'msg' =>  'Save Changes Successfull'
          ]
      ]);
    }

    public function AddUser(Request $request){

      $this->Request = $request;
      $this->User = new User;

      switch ($this->Request->input('type')) {
        case 'teacher':
          $data = Teacher::findOrfail($this->Request->input('teacher'));
          $this->User->user_type = 'teacher';
          $this->User->role = 'Teacher';
          break;

        case 'employee':
          $data = Employee::findOrfail($this->Request->input('employee'));
          $this->User->user_type = 'employee';
          $this->User->role = $data->role;
          break;

        default:
          return redirect()->back()->withInput()
           ->withErrors([
                   'type' => 'You must be select User Type',
               ]);
          break;
      }

      $this->User->foreign_id = $data->id;
      $this->User->contact_no = $data->phone;

      $this->PostValidate();
      $this->SetAttributes();
      $this->User->name = $this->Request->input('name');
      $this->User->email = $this->Request->input('email');
      $this->User->password = bcrypt($this->Request->input('password'));
      $this->User->created_by  = Auth::user()->id;
      $this->User->save();

      $data->user_id = $this->User->id;
      $data->save();

      $this->SetPrivileges();

      return redirect('users')->with([
        'toastrmsg' => [
          'type' => 'success', 
          'title'  =>  'Users Registration',
          'msg' =>  'Registration Successfull'
          ]
      ]);

    }

    protected function SetAttributes(){

      $this->User->active = $this->Request->input('status');
      $this->User->allow_content = "Admin";
      $this->User->academic_session = Auth::user()->academic_session;
      $this->User->settings   = Auth::user()->settings;

    }

    protected function SetPrivileges(){
      $privileges = config('privileges');
      if ($this->Request->has('privileges')) {
        foreach($this->Request->input('privileges') as $key => $value) {
          $privileges[$key]['default']  = (Auth::user()->getprivileges->privileges->$key->default)? 1 : 0;
          if (isset($value['options'])) {
            foreach ($value['options'] as $v) {
                $privileges[$key][$v] = (Auth::user()->getprivileges->privileges->$key->$v)? 1 : 0;
            }
          }
        }
      }

        UserPrivilege::updateOrCreate(
            ['user_id' => $this->User->id],
            ['privileges' => $privileges]
          );
    }


    protected function Content(){
      $content = AdminContent::select('id','label', 'options')
                                ->where('type', 'parent-content')
                                ->Orwhere('type', 'child-content')
//                                ->orderBy('order_no')
                                ->get();
      foreach ($content as $key => $value) {
        if(Auth::user()->getprivileges->NavPrivileges($value->id, 'default')) {
          $this->data['content'][]  = $value;
        }
      }
    }

}
