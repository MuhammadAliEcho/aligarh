<?php

namespace App\Http\Controllers\Admin;

use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Employee;
use App\Teacher;
use App\User;
use Auth;

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
        'name'              => $request->input('name'),
        'email'             => $request->input('email'),
        'password'          => bcrypt($request->input('password')),
        'active'            => $request->input('status'),
        'academic_session'  => Auth::user()->academic_session,
        'allow_session'   =>  $request->input('allow_session'),
        'settings'          => Auth::user()->settings,
        'foreign_id'        => $data->id,
        'contact_no'        => $data->phone,
        'user_type'         => $request->input('type'),
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
          'status'          =>  'required',
          'allow_session'   =>  'required',
          'password'        =>  'sometimes|nullable|between:6,12',
          're_password'     =>  'sometimes|nullable|between:6,12|same:password',
      ]);


      // if (Auth::user()->getprivileges->privileges->{$this->data['root']['content']['id']}->editpwd
      if (isset($validatedData['password'])) {
          $validatedData['password'] = Hash::make($validatedData['password']);
      }

      $User->update([
        'active'            =>  $validatedData['status'],
        'allow_session'     =>  $validatedData['allow_session'],
        'password'          =>  $validatedData['password'],
        'academic_session'  =>  Auth::user()->academic_session,
        'settings'          =>  Auth::user()->settings,
      ]);

      return redirect('users')->with([
        'toastrmsg' => [
          'type' => 'success', 
          'title'  =>  'Users Registration',
          'msg' =>  'Save Changes Successfull'
          ]
      ]);
    }
}
