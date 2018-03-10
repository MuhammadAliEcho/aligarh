<?php

namespace App\Http\Controllers;

use Yajra\Datatables\Facades\Datatables;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\User;
use App\Teacher;
use App\Employee;
use App\AdminContent;
use Auth;

class UsersController extends Controller
{

    protected $data, $User, $Request;

    public function __Construct($Routes){
      $this->data['root'] = $Routes;
    }

    protected function PostValidate(){
      $this->validate($this->Request, [
          'name'  =>  'sometimes|required|unique:users,name'. (($this->data['root']['option'] !== '')? ','.$this->User->id : ''),
          'email' =>  'sometimes|required|email|unique:users,email'. (($this->data['root']['option'] !== '')? ','.$this->User->id : ''),
          'status'  =>  'required',
          'password' =>  'sometimes|between:6,12',
          're_password'  =>  'sometimes|between:6,12|same:password',
      ]);
    }

    public function GetUsers(){
      $this->Content();
      return view('users', $this->data);
    }

    public function AjaxGetUser(){
      return Datatables::eloquent(User::select('id', 'name', 'email', 'role', 'employee_id', 'teacher_id'))->make(true);
    }

    public function EditUser(){
      $this->Content();
      $this->data['user'] = User::findOrfail($this->data['root']['option']);
      return view('edit_user', $this->data);
    }

    public function PostEditUser(Request $request){

      $this->Request = $request;
      $this->User = User::findOrfail($this->data['root']['option']);
        if($this->User->user_id == 0){
        return redirect('users')->with([
        'toastrmsg' => [
          'type' => 'warning', 
          'title'  =>  'Users Registration',
          'msg' =>  'Sorry '.$this->User->name.' User Cant be Edited In Development version'
          ]
        ]);
        }

      $this->PostValidate();
      $this->SetAttributes();
      if (Auth::user()->privileges->{$this->data['root']['content']['id']}->editpwd && !empty($this->Request->input('password'))) {
        $this->User->password = bcrypt($this->Request->input('password'));
      }
      
      $this->User->save();

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
          $this->User->teacher_id = $data->id;
          $this->User->user_type = 'teacher';
          $this->User->role = 'teacher';
          $this->User->contact_no = $data->phone;
          break;

        case 'employee':
          $data = Employee::findOrfail($this->Request->input('employee'));
          $this->User->employee_id = $data->id;
          $this->User->user_type = 'employee';
          $this->User->role = $data->role;
          $this->User->contact_no = $data->phone;
          break;
        
        default:
          return redirect()->back()->withInput()
           ->withErrors([
                   'type' => 'You must be select User Type',
               ]);
          break;
      }
      $this->PostValidate();
      $this->SetAttributes();
      $this->User->name = $this->Request->input('name');
      $this->User->email = $this->Request->input('email');
      $this->User->password = bcrypt($this->Request->input('password'));
      $this->User->user_id  = Auth::user()->id;
      $this->User->save();

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

      $privileges = config('privileges');
      if ($this->Request->has('privileges')) {
        foreach($this->Request->input('privileges') as $key => $value) {
          $privileges[$key]['default']  = (Auth::user()->privileges->$key->default)? 1 : 0;
          if (isset($value['options'])) {
            foreach ($value['options'] as $v) {
                $privileges[$key][$v] = (Auth::user()->privileges->$key->$v)? 1 : 0;
            }
          }
        }
      }

      $this->User->privileges = $privileges;

    }

    protected function Content(){
      $content = AdminContent::select('id','label', 'options')
                                ->where('type', 'parent-content')
                                ->Orwhere('type', 'child-content')
//                                ->orderBy('order_no')
                                ->get();
      foreach ($content as $key => $value) {
        if(Auth::user()->NavPrivileges($value->id, 'default')) {
          $this->data['content'][]  = $value;
        }
      }
    }

}
