<?php

namespace App\Http\Controllers\Admin;

use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use App\Teacher;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Auth;
use DB;
use App\Http\Controllers\Controller;

class TeacherController extends Controller
{

  public function GetImage($id){
    $teacher  = Teacher::findorfail($id);
    //$image = Storage::get($teacher->image_dir);
    //$image = Storage::disk('public/studnets')->get('1.jpg');
    //return Response($image, 200);
    if(Storage::exists($teacher->image_dir)){
      return Response(Storage::get($teacher->image_dir), 200)->header('Content-Type', 'image');
    }

    return abort(404);
  
  }

  public function GetProfile($id){
    $data['teacher']  = Teacher::findorfail($id);
    return view('admin.teacher_profile', $data);
  }

  protected function PostValidate($request){
    $this->validate($request, [
        'name'      =>  'required',
        //'subject'   =>  'required',
        'gender'    =>  'required',
        //'email'     =>  'required|email',
        'qualification'  =>  'required',
        'salary'      =>  'required|numeric',
        'img'       => 	'image|mimes:jpg,jpeg|max:2048'
    ]);
  }

  public function GetTeacher(Request $request){
    $data['teachers'] = Teacher::select('name', 'email', 'address', 'id', 'phone')->get();
    if($request->ajax()){
      return DataTables::queryBuilder(DB::table('teachers')
                                          ->leftJoin('users', 'teachers.user_id', '=', 'users.id')
                                          ->select('teachers.name', 'teachers.email', 'teachers.address', 'teachers.id', 'teachers.phone', 'users.active', 'users.id AS user_id'))->make(true);
    }
    
    return view('admin.teacher', $data);
  }

  public function Grid(Request $request){

    $Teachers = Teacher::query(); 

		if ($request->filled('search_teachers')) {
			$search = $request->input('search_teachers');

			$Teachers->where(fn($query) => 
			$query->where('name', 'like', "%{$search}%")
				->orWhere('email', 'like', "%{$search}%")
				->orWhere('phone', 'like', "%{$search}%")
				->orWhere('gender', 'like', "%{$search}%")
				->orWhere('qualification', 'like', "%{$search}%")
			);
		}

		$Teachers = $request->filled('per_page') ? $Teachers->paginate($request->input('per_page')) : $Teachers->get();
		
		return response()->json($Teachers);
  }

  public function FindTeacher(Request $request){
    if ($request->ajax()) {
      $teachers = Teacher::where('name', 'LIKE', '%'.$request->input('q').'%')
                ->orwhere('email', 'LIKE', '%'.$request->input('q').'%')
                  ->get();
                  $k=0;
      foreach ($teachers as $teacher) {
        if ($teacher->User == null) {
          $data[$k]['id'] = $teacher->id;
          $data[$k]['text'] = $teacher->name.' | '.$teacher->email;
          $data[$k]['email']  = $teacher->email;
          $data[$k]['name']  = $teacher->name;
          $data[$k]['role']  = "Teacher";
          // $data[$k]['htm1'] = '<span class="text-danger">';
          // $data[$k]['htm2'] = '</span>';
          $k++;
        }
      }
      return response(isset($data)? $data : [0 => ['text' => 'No Data Available']]);
    }
    return abort(404);
  }

  public function EditTeacher($id){
    if(Teacher::where('id', $id)->count() == 0){
    return  redirect('teacher')->with([
        'toastrmsg' => [
          'type' => 'warning', 
          'title'  =>  '# Invalid URL',
          'msg' =>  'Do Not write hard URL\'s'
          ]
      ]);
    }

    $data['teacher'] = Teacher::find($id);
    return view('admin.edit_teacher', $data);
  }

  public function PostEditTeacher(Request $request, $id){

    if(Teacher::where('id', $id)->count() == 0){
    return  redirect('teacher')->with([
        'toastrmsg' => [
          'type' => 'warning', 
          'title'  =>  '# Invalid URL',
          'msg' =>  'Do Not write hard URL\'s'
          ]
      ]);
    }

    $Teacher = Teacher::find($id);
    $this->PostValidate($request);
    $this->SetAttributes($Teacher, $request);
    if($request->hasFile('img')){
      $this->SaveImage($Teacher, $request);
    }
    $Teacher->updated_by  = Auth::user()->id;
    $Teacher->save();
    if ($Teacher->User) {
      $Teacher->User->email   =  $Teacher->email;
      $Teacher->User->contact_no   =  $Teacher->phone;
      $Teacher->User->save();
    }

    return redirect('teacher')->with([
        'toastrmsg' => [
          'type' => 'success', 
          'title'  =>  'Teacher Registration',
          'msg' =>  'Save Changes Successfull'
          ]
      ]);
  }

  public function AddTeacher(Request $request){
    $this->PostValidate($request);
    $Teacher = new Teacher;
    $this->SetAttributes($Teacher, $request);
    $Teacher->created_by  = Auth::user()->id;
    $Teacher->save();
    if($request->hasFile('img')){
      $this->SaveImage($Teacher, $request);
    }

    $Teacher->save();

    return redirect('teacher')->with([
        'toastrmsg' => [
          'type' => 'success', 
          'title'  =>  'Teacher Registration',
          'msg' =>  'Registration Successfull'
          ]
      ]);

  }

  protected function SetAttributes($Teacher, $request){
    $Teacher->name = $request->input('name');
    $Teacher->f_name = $request->input('f_name');
    $Teacher->husband_name = $request->input('husband_name');
    $Teacher->subject = $request->input('subject');
    $Teacher->gender = $request->input('gender');
    $Teacher->email = $request->input('email');
    $Teacher->qualification = $request->input('qualification');
    $Teacher->relegion = $request->input('relegion');
    $Teacher->salary = $request->input('salary');
    $Teacher->address = $request->input('address');
    $Teacher->phone = $request->input('phone');
  }


  protected function SaveImage($Teacher, $request){
    $file = $request->file('img');
    Storage::delete($Teacher->image_dir);
    $extension = $file->getClientOriginalExtension();
    Storage::disk('public')->put('teachers/'.$Teacher->id.'.'.$extension,  File::get($file));
    //$file = $request->file('img')->storePubliclyAs('images/teachers', $Teacher->id.'.'.$file->getClientOriginalExtension(), 'public');
    $Teacher->image_dir = 'public/teachers/'.$Teacher->id.'.'.$extension;
    $Teacher->image_url = 'teacher/image/'.$Teacher->id;
  }

}
