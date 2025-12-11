<?php

namespace App\Http\Controllers\Admin;

use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Model\Employee;
use App\Model\Role;
use App\Model\Teacher;
use App\Model\User;

class UsersController extends Controller
{
  public function index(Request $request)
  {
    $Roles = Role::notDeveloper()->select('id', 'name')->get();
    if ($request->ajax()) {

      $query = User::with('roles')->select('id', 'name', 'email', 'foreign_id', 'user_type', 'active')->NotDeveloper()->staff();

      return DataTables::eloquent($query)
        ->addColumn('roles', function (User $user) {
          return $user->roles->pluck('name')->join(', ');
        })
        ->make(true);
    }
    return view('admin.users', compact('Roles'));
  }


  public function create(Request $request)
  {

    $validator = Validator::make(
      $request->all(),
      [
        'name' => 'required|unique:users,name',
        'type' => 'required|in:teacher,employee',
        'email' => 'required|email|unique:users,email',
        'status' => 'required|in:0,1',
        'allow_session' => 'required',
        'password' => 'required|between:6,12',
        're_password' => 'required|between:6,12|same:password',
        'role' => 'required|exists:roles,id',
      ],
      [
        'name.required' => 'The name field is required.',
        'name.unique' => 'This name is already taken.',

        'type.required' => 'Type is required.',
        'type.in' => 'Type must be teacher or employee.',

        'email.required' => 'Email is required.',
        'email.email' => 'Please enter a valid email address.',
        'email.unique' => 'This email is already registered.',

        'status.required' => 'Status is required.',
        'status.in' => 'Status must be 0 (inactive) or 1 (active).',

        'allow_session.required' => 'Allow session selection is required.',

        'password.required' => 'Password is required.',
        'password.between' => 'Password must be between 6 and 12 characters.',

        're_password.required' => 'Re-entering the password is required.',
        're_password.between' => 'Re-entered password must be between 6 and 12 characters.',
        're_password.same' => 'Passwords do not match.',

        'role.required' => 'Role is required.',
        'role.exists' => 'Selected role is invalid.',
      ]

    );

    if ($validator->fails()) {
      return redirect()->back()
        ->withErrors($validator)
        ->withInput()
        ->with([
          'toastrmsg' => [
            'type' => 'error',
            'title' => 'Users',
            'msg' => __('modules.users_create_error')
          ]
        ]);
    }

    switch ($request->input('type')) {
      case 'employee':
        $data = Employee::findOrfail($request->input('employee'));
        break;

      case 'teacher':
        $data = Teacher::findOrfail($request->input('teacher'));
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

    $Role = Role::findOrfail($request->input('role'));
    $User->assignRole($Role->name);
    $data->user_id = $User->id;
    $data->save();

    return redirect('users')->with([
      'toastrmsg' => [
        'type' => 'success',
        'title'  =>  'Users Registration',
        'msg' =>  __('modules.common_register_success')
      ]
    ]);
  }

  public function edit($id)
{
    $user = User::where('id', $id)->Staff()->NotDeveloper()->firstOrFail();
    $roles = Role::NotDeveloper()->select('id', 'name')->get();
    return view('admin.edit_user', [
        'user' => $user,
        'roles' => $roles,
        'userRole' => $user->roles->pluck('id')->first()
    ]);
}


  public function update(Request $request, $id)
  {
    $User =  User::where('id', $id)->Staff()->NotDeveloper()->firstOrFail();
    $validatedData = $request->validate([
      'active'            => 'required',
      'allow_session'     => 'required|array',
      'password'          => 'nullable|between:6,12',
      're_password'       => 'nullable|between:6,12|same:password',
      'role'              => 'required|exists:roles,id',
    ]);

    if (Auth::user()->can('users.update.update_password') && !empty($validatedData['password'])) {
      $validatedData['password'] = bcrypt($validatedData['password']);
    } else {
      unset($validatedData['password']);
    }

    $validatedData['academic_session'] = (int) collect($request->input('allow_session', []))->last();

    $User->update($validatedData);

    $role = Role::findOrFail($request->input('role'));
    $User->syncRoles($role);

    return redirect('users')->with([
      'toastrmsg' => [
        'type' => 'success',
        'title'  =>  'Users Registration',
        'msg' =>  __('modules.common_save_success')
      ]
    ]);
  }

  public function loginAsUser($id)
  {
    $user = User::findOrfail($id);
    Auth::login($user);
    return redirect('/');
  }
}
