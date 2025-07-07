<?php

namespace App\Http\Controllers\Admin;

use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Employee;
use App\Teacher;
use App\User;

class UsersController extends Controller
{
    public function index(Request $request)
    {
      if ($request->ajax()) {
         $query = User::with('roles')->select('id', 'name', 'email', 'foreign_id', 'user_type', 'active')->NotDeveloper()->staff();

        return DataTables::eloquent($query)
            ->addColumn('roles', function (User $user) {
                return $user->roles->pluck('name')->join(', ');
            })
            ->make(true);
      }
      return view('admin.users');
    }


    public function create(Request $request)
    {
      $request->validate([
          'name'            =>  'required|unique:users,name',
          'email'           =>  'required|email|unique:users,email',
          'status'          =>  'required',
          'allow_session'   =>  'required',
          'password'        =>  'required|between:6,12',
          're_password'     =>  'required|between:6,12|same:password',
      ]);

      switch ($request->input('type')) {
        case 'employee':
          $data = Employee::findOrfail($request->input('employee'));
          $role_id = 3;
          break;

        case 'teacher':
          $data = Teacher::findOrfail($request->input('teacher'));
          $role_id = 4; 
          break;

        default:
          return redirect()->back()->withInput()
           ->withErrors([
                   'type' => 'You must be select User Type',
               ]);
          break;
      }

      $User = User::create([
        'name'              =>  $request->input('name'),
        'email'             =>  $request->input('email'),
        'password'          =>  bcrypt($request->input('password')),
        'active'            =>  $request->input('status'),
        'allow_session'     =>  $request->input('allow_session'),
        'foreign_id'        =>  $data->id,
        'contact_no'        =>  $data->phone,
        'user_type'         =>  $request->input('type'),
      ]);

      $User->assignRole($role_id);
      $data->user_id = $User->id;
      $data->save();

      return redirect('users')->with([
        'toastrmsg' => [
          'type' => 'success', 
          'title'  =>  'Users Registration',
          'msg' =>  'Registration Successfull'
          ]
      ]);
    }

    public function edit($id){
      $data['user'] = User::where('id', $id)->Staff()->NotDeveloper()->firstOrFail();
      return view('admin.edit_user', $data);
    }


    public function update(Request $request, $id){
      $User =  User::where('id', $id)->Staff()->NotDeveloper()->firstOrFail();
      $validatedData  = $request->validate([
          'active'            =>  'required',
          'allow_session'     =>  'sometimes|required',
          'password'          =>  'nullable|between:6,12',
          're_password'       =>  'nullable|between:6,12|same:password',
      ]);
      if(Auth::user()->can('users.update.update_password') && !empty(isset($validatedData['password']))){
        $validatedData['password'] = bcrypt($validatedData['password']);
      } else {
        unset($validatedData['password']);
      }

      $User->update($validatedData);

      return redirect('users')->with([
        'toastrmsg' => [
          'type' => 'success', 
          'title'  =>  'Users Registration',
          'msg' =>  'Save Changes Successfull'
          ]
      ]);
    }
}
