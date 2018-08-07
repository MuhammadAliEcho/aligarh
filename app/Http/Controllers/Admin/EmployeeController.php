<?php

namespace App\Http\Controllers\Admin;

use Yajra\Datatables\Facades\Datatables;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Employee;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Auth;
use DB;
use App\Http\Controllers\Controller;

class EmployeeController extends Controller
{

//  protected $Routes;
  protected $data, $Employee, $Request;

  public function __Construct($Routes, Request $Request){
    $this->data['root'] = $Routes;
    $this->Request = $Request;
  }

  public function GetImage(){
    $Employee  = Employee::findOrFail($this->data['root']['option']);
    $image = Storage::get($Employee->img_dir);
//    $image = Storage::disk('public/studnets')->get('1.jpg');
//    return Response($image, 200);
    return Response($image, 200)->header('Content-Type', 'image');
  }

  public function GetProfile(){
    $this->data['employee']  = Employee::findOrFail($this->data['root']['option']);
    return view('admin.employee_profile', $this->data);
  }

  protected function PostValidate(){
    $this->validate($this->Request, [
        'name'      =>  'required',
//        'subject'   =>  'required',
        'gender'    =>  'required',
//        'email' =>  'required|email|unique:employees,email'. (($this->data['root']['option'] !== '')? ','.$this->Employee->id : ''),
//        'email'     =>  'required|email',
        'role'      =>  'required',
//        'qualification'  =>  'required',
        'salary'      =>  'required|numeric',
        'img'         =>  'image|mimes:jpeg,png,jpg|max:4096'
    ]);
  }

  public function GetEmployee(){

    if ($this->Request->ajax()) {
      return Datatables::queryBuilder(DB::table('employees')
                                          ->leftJoin('users', 'employees.user_id', '=', 'users.id')
                                          ->select('employees.name', 'employees.email', 'employees.role', 'employees.id', 'employees.phone', 'users.active', 'users.id AS user_id'))
                                          ->make(true);
    }

    return view('admin.employee', $this->data);
  }

  public function FindEmployee(){
    if ($this->Request->ajax()) {
      $employees = Employee::where('name', 'LIKE', '%'.$this->Request->input('q').'%')
                  ->orwhere('email', 'LIKE', '%'.$this->Request->input('q').'%')
                  ->orwhere('role', 'LIKE', '%'.$this->Request->input('q').'%')
                  ->get();
                  $k = 0;
      foreach ($employees as $employee) {
        if ($employee->User == null) {
          $data[$k]['id'] = $employee->id;
          $data[$k]['text'] = $employee->name.' | '.$employee->email.' | '.$employee->role;
          $data[$k]['email']  = $employee->email;
          $data[$k]['role']  = $employee->role;
          $data[$k]['name']  = $employee->name;
  /*        $data[$k]['htm1'] = '<span class="text-danger">';
          $data[$k]['htm2'] = '</span>';*/
          $k++;
        }
      }
      return response(isset($data)? $data : [0 => ['text' => 'No Data Available']]);
   }
    return abort(404);
  }

  public function EditEmployee(){

    if(Employee::where('id', $this->data['root']['option'])->count() == 0){
    return  redirect('employee')->with([
        'toastrmsg' => [
          'type' => 'warning', 
          'title'  =>  '# Invalid URL',
          'msg' =>  'Do Not write hard URL\'s'
          ]
      ]);
    }

    $this->data['employee'] = Employee::find($this->data['root']['option']);
    return view('admin.edit_employee', $this->data);
  }

  public function PostEditEmployee(Request $request){

    $this->Request = $request;

    if(Employee::where('id', $this->data['root']['option'])->count() == 0){
    return  redirect('employee')->with([
        'toastrmsg' => [
          'type' => 'warning', 
          'title'  =>  '# Invalid URL',
          'msg' =>  'Do Not write hard URL\'s'
          ]
      ]);
    }

    $this->Employee = Employee::findOrFail($this->data['root']['option']);
        if($this->Employee->created_by == 0 && Auth::user()->id != 1){
        return redirect('employee')->with([
        'toastrmsg' => [
          'type' => 'warning', 
          'title'  =>  'Employees Registration',
          'msg' =>  'Sorry '.$this->Employee->name.' Employee Can\'t be Editable'
          ]
        ]);
        }
    $this->PostValidate();
    $this->SetAttributes();
    if($this->Request->hasFile('img')){
      $this->SaveImage();
    }
    $this->Employee->updated_by  = Auth::user()->id;
    $this->Employee->save();

    return redirect('employee')->with([
        'toastrmsg' => [
          'type' => 'success',
          'title'  =>  'Employee Registration',
          'msg' =>  'Save Changes Successfull'
          ]
      ]);
  }

  public function AddEmployee(Request $request){
    $this->Request = $request;
    $this->PostValidate();
    $this->Employee = new Employee;
    $this->SetAttributes();
    $this->Employee->created_by  = Auth::user()->id;
    $this->Employee->save();
    if($this->Request->hasFile('img')){
      $this->SaveImage();
    }

    $this->Employee->save();

    return redirect('employee')->with([
        'toastrmsg' => [
          'type' => 'success', 
          'title'  =>  'Employee Registration',
          'msg' =>  'Registration Successfull'
          ]
      ]);
  }

  protected function SetAttributes(){
    $this->Employee->name = $this->Request->input('name');
    $this->Employee->gender = $this->Request->input('gender');
    $this->Employee->email = $this->Request->input('email');
    $this->Employee->role = $this->Request->input('role');
    $this->Employee->qualification = $this->Request->input('qualification');
    $this->Employee->salary = $this->Request->input('salary');
    $this->Employee->address = $this->Request->input('address');
    $this->Employee->relegion = $this->Request->input('relegion');
    $this->Employee->phone = $this->Request->input('phone');
  }


  protected function SaveImage(){
    $file = $this->Request->file('img');
    Storage::delete($this->Employee->img_dir);
    $extension = $file->getClientOriginalExtension();
    Storage::disk('public')->put('employee/'.$this->Employee->id.'.'.$extension,  File::get($file));
//    $file = $this->Request->file('img')->storePubliclyAs('images/employee', $this->Employee->id.'.'.$file->getClientOriginalExtension(), 'public');
    $this->Employee->img_dir = 'public/employee/'.$this->Employee->id.'.'.$extension;
    $this->Employee->img_url = 'employee/image/'.$this->Employee->id;
  }

}
