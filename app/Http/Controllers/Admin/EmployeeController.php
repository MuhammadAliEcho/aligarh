<?php

namespace App\Http\Controllers\Admin;

use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use App\Employee;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Auth;
use DB;
use App\Http\Controllers\Controller;

class EmployeeController extends Controller
{
  public function GetImage($id)
  {
    $Employee = Employee::findOrFail($id);

    // Check if file exists in the current tenant's storage
    if (!Storage::exists($Employee->img_dir)) {
      abort(404, 'Image not found.');
    }

    // Get the image content using the default storage (which handles tenancy)
    $image = Storage::get($Employee->img_dir);

    // Get MIME type
    $mime = Storage::mimeType($Employee->img_dir);

    return response($image, 200)->header('Content-Type', $mime ?? 'image/jpeg');
  }

  public function GetProfile($id){
    $data['employee']  = Employee::findOrFail($id);
    return view('admin.employee_profile', $data);
  }

  protected function PostValidate($request){
    $this->validate($request, [
        'name'      =>  'required',
        //'subject'   =>  'required',
        'gender'    =>  'required',
        'religion'    =>  'required',
        //'email' =>  'required|email|unique:employees,email'. (($this->data['root']['option'] !== '')? ','.$this->Employee->id : ''),
        //'email'     =>  'required|email',
        'role'      =>  'required',
        //'qualification'  =>  'required',
        'salary'      =>  'required|numeric',
        'img'       =>    'image|mimes:jpg,jpeg,png|max:100',
        'date_of_birth' => 'required|date|date_format:Y-m-d',
        'date_of_joining' => 'required|date|date_format:Y-m-d',
        'id_card' => 'required|string|max:255|unique:employees,id_card'. (($request->route('id'))? ','.$request->route('id') : ''),
    ]);
  }

  public function GetEmployee(Request $request){

    if ($request->ajax()) {
      return DataTables::queryBuilder(DB::table('employees')
                                          ->leftJoin('users', 'employees.user_id', '=', 'users.id')
                                          ->where(fn($query) => 
                                              $query->where('users.id', '!=', 1)
                                                    ->orWhereNull('users.id')
                                          )
                                          ->select('employees.name', 'employees.email', 'employees.role', 'employees.id', 'employees.phone', 'users.active', 'users.id AS user_id'))
                                          ->make(true);
    }

    return view('admin.employee');
  }


  public function Grid(Request $request){

    $Employees = Employee::query()->NotDeveloper(); 

		if ($request->filled('search_employees')) {
			$search = $request->input('search_employees');

			$Employees->where(fn($query) => 
			$query->where('name', 'like', "%{$search}%")
				->orWhere('email', 'like', "%{$search}%")
				->orWhere('phone', 'like', "%{$search}%")
				->orWhere('gender', 'like', "%{$search}%")
				->orWhere('qualification', 'like', "%{$search}%")
			);
		}

		$Employees = $request->filled('per_page') ? $Employees->paginate($request->input('per_page')) : $Employees->get();
		
		return response()->json($Employees);
  }


  public function FindEmployee(Request $request){
  if ($request->ajax()) {
    $employees = Employee::where('name', 'LIKE', '%'.$request->input('q').'%')
                ->orwhere('email', 'LIKE', '%'.$request->input('q').'%')
                ->orwhere('role', 'LIKE', '%'.$request->input('q').'%')
                  ->get();
                  $k = 0;
      foreach ($employees as $employee) {
        if ($employee->User == null) {
          $data[$k]['id'] = $employee->id;
          $data[$k]['text'] = $employee->name.' | '.$employee->email.' | '.$employee->role;
          $data[$k]['email']  = $employee->email;
          $data[$k]['role']  = $employee->role;
          $data[$k]['name']  = $employee->name;
          /*$data[$k]['htm1'] = '<span class="text-danger">';
          $data[$k]['htm2'] = '</span>';*/
          $k++;
        }
      }
      return response(isset($data)? $data : [0 => ['text' => 'No Data Available']]);
   }
    return abort(404);
  }

  public function EditEmployee($id){

    if(Employee::where('id', $id)->count() == 0){
    return  redirect('employee')->with([
        'toastrmsg' => [
          'type' => 'warning', 
          'title'  =>  '# Invalid URL',
          'msg' =>  'Do Not write hard URL\'s'
          ]
      ]);
    }

    $data['employee'] = Employee::find($id);
    return view('admin.edit_employee', $data);
  }

  public function PostEditEmployee(Request $request, $id){

    if(Employee::where('id', $id)->count() == 0){
    return  redirect('employee')->with([
        'toastrmsg' => [
          'type' => 'warning', 
          'title'  =>  '# Invalid URL',
          'msg' =>  'Do Not write hard URL\'s'
          ]
      ]);
    }

    $Employee = Employee::findOrFail($id);
        if($Employee->created_by == 0 && Auth::user()->id != 1){
        return redirect('employee')->with([
        'toastrmsg' => [
          'type' => 'warning', 
          'title'  =>  'Employees Registration',
          'msg' =>  'Sorry '.$Employee->name.' Employee Can\'t be Editable'
          ]
        ]);
        }
    $this->PostValidate($request);
    $this->SetAttributes($Employee, $request);

    if ($request->hasFile('img')) {
      $this->SaveImage($Employee, $request);
    } elseif ($request->input('removeImage')) {
      $this->DeleteImage($Employee);
    }

    $Employee->updated_by  = Auth::user()->id;
    $Employee->save();

    if ($Employee->User) {
      $Employee->User->email   =  $Employee->email;
      $Employee->User->contact_no   =  $Employee->phone;
      $Employee->User->save();
    }

    return redirect('employee')->with([
        'toastrmsg' => [
          'type' => 'success',
          'title'  =>  'Employee Update',
          'msg' =>  'Save Changes Successfull'
          ]
      ]);
  }

  public function AddEmployee(Request $request){
    $this->PostValidate($request);
    $Employee = new Employee;
    $this->SetAttributes($Employee, $request);
    $Employee->created_by  = Auth::user()->id;
    $Employee->save();
    if($request->hasFile('img')){
      $this->SaveImage($Employee, $request);
    }

    $Employee->save();

    return redirect('employee')->with([
        'toastrmsg' => [
          'type' => 'success', 
          'title'  =>  'Employee Registration',
          'msg' =>  'Registration Successfull'
          ]
      ]);
  }

  protected function SetAttributes($Employee, $request){
    $Employee->name = $request->input('name');
    $Employee->gender = $request->input('gender');
    $Employee->email = $request->input('email');
    $Employee->role = $request->input('role');
    $Employee->qualification = $request->input('qualification');
    $Employee->salary = $request->input('salary');
    $Employee->address = $request->input('address');
    $Employee->religion = $request->input('religion');
    $Employee->phone = $request->input('phone');
    $Employee->date_of_birth = $request->input('date_of_birth');
    $Employee->date_of_joining = $request->input('date_of_joining');
    $Employee->id_card = $request->input('id_card');
  }


  protected function SaveImage($Employee, $request)
  {
    $file = $request->file('img');

    if ($Employee->img_dir && Storage::exists($Employee->img_dir)) {
      Storage::delete($Employee->img_dir);
    }

    $extension = $file->getClientOriginalExtension();
    $filename = $Employee->id;

    $path = 'employee/' . $filename;
    Storage::put($path . '.' . $extension, File::get($file));

    $Employee->img_dir = "{$path}" . '.' . $extension;
    $Employee->img_url = 'employee/image/' . $filename;
  }

  protected function DeleteImage($Employee)
  {
    if ($Employee->img_dir) {
      Storage::delete($Employee->img_dir);
      $Employee->img_dir = null;
      $Employee->img_url = null;
      $Employee->save();
    }
  }
}
