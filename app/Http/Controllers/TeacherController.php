<?php

namespace App\Http\Controllers;

use Yajra\Datatables\Facades\Datatables;
use Illuminate\Http\Request;
//use Request;
use App\Http\Requests;
use App\Teacher;
use App\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Auth;
use DB;

class TeacherController extends Controller
{

//  protected $Routes;
  protected $data, $Teacher, $Request;

  public function __Construct($Routes, Request $Request){
    $this->Request  = $Request;
    $this->data['root'] = $Routes;
  }

  public function GetImage(){
    $teacher  = Teacher::findorfail($this->data['root']['option']);
    //$image = Storage::get($teacher->image_dir);
//    $image = Storage::disk('public/studnets')->get('1.jpg');
//    return Response($image, 200);
    if(Storage::exists($teacher->image_dir)){
      return Response(Storage::get($teacher->image_dir), 200)->header('Content-Type', 'image');
    }

    return abort(404);
  
  }

  public function GetProfile(){
    $this->data['teacher']  = Teacher::findorfail($this->data['root']['option']);
    return view('teacher_profile', $this->data);
  }

  protected function PostValidate(){
    $this->validate($this->Request, [
        'name'      =>  'required',
//        'subject'   =>  'required',
        'gender'    =>  'required',
//        'email'     =>  'required|email',
        'qualification'  =>  'required',
        'salary'      =>  'required|numeric',
        'img'         =>  'image|mimes:jpeg,png,jpg|max:4096'
    ]);
  }

  public function GetTeacher(){
    //$this->data['teachers'] = Teacher::select('name', 'email', 'address', 'id', 'phone')->get();
    return view('teacher', $this->data);
  }

  public function AjaxGetTeacher(){
    //return Datatables::eloquent(Teacher::select('name', 'email', 'address', 'id', 'phone'))->make(true);
    return Datatables::queryBuilder(DB::table('teachers')
                                        ->leftJoin('users', 'teachers.id', '=', 'users.teacher_id')
                                        ->select('teachers.name', 'teachers.email', 'teachers.address', 'teachers.id', 'teachers.phone', 'users.teacher_id', 'users.active', 'users.id AS user_id'))->make(true);
  }

  public function FindTeacher(){
    if ($this->Request->ajax()) {
      $teachers = Teacher::where('name', 'LIKE', '%'.$this->Request->input('q').'%')
                ->orwhere('email', 'LIKE', '%'.$this->Request->input('q').'%')
                  ->get();
                  $k=0;
      foreach ($teachers as $teacher) {
        if ($teacher->User == null) {
          $data[$k]['id'] = $teacher->id;
          $data[$k]['text'] = $teacher->name.' | '.$teacher->email;
          $data[$k]['email']  = $teacher->email;
          $data[$k]['name']  = $teacher->name;
          $data[$k]['role']  = "Teacher";
  /*        $data[$k]['htm1'] = '<span class="text-danger">';
          $data[$k]['htm2'] = '</span>';*/
          $k++;
        }
      }
      return response(isset($data)? $data : [0 => ['text' => 'No Data Available']]);
    }
    return abort(404);
  }

  public function EditTeacher(){
    if(Teacher::where('id', $this->data['root']['option'])->count() == 0){
    return  redirect('teacher')->with([
        'toastrmsg' => [
          'type' => 'warning', 
          'title'  =>  '# Invalid URL',
          'msg' =>  'Do Not write hard URL\'s'
          ]
      ]);
    }

    $this->data['teacher'] = Teacher::find($this->data['root']['option']);
    return view('edit_teacher', $this->data);
  }

  public function PostEditTeacher(Request $request){

    $this->Request = $request;

    if(Teacher::where('id', $this->data['root']['option'])->count() == 0){
    return  redirect('teacher')->with([
        'toastrmsg' => [
          'type' => 'warning', 
          'title'  =>  '# Invalid URL',
          'msg' =>  'Do Not write hard URL\'s'
          ]
      ]);
    }

    $this->Teacher = Teacher::find($this->data['root']['option']);
    $this->PostValidate();
    $this->SetAttributes();
    if($this->Request->hasFile('img')){
      $this->SaveImage();
    }
    $this->Teacher->updated_by  = Auth::user()->id;
    $this->Teacher->save();
    if ($this->Teacher->User) {
      $this->Teacher->User->email   =  $this->Teacher->email;
      $this->Teacher->User->contact_no   =  $this->Teacher->phone;
      $this->Teacher->User->save();
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
    $this->Request = $request;
    $this->PostValidate();
    $this->Teacher = new Teacher;
    $this->SetAttributes();
    $this->Teacher->created_by  = Auth::user()->id;
    $this->Teacher->save();
    if($this->Request->hasFile('img')){
      $this->SaveImage();
    }

    $this->Teacher->save();

    return redirect('teacher')->with([
        'toastrmsg' => [
          'type' => 'success', 
          'title'  =>  'Teacher Registration',
          'msg' =>  'Registration Successfull'
          ]
      ]);

  }

  protected function SetAttributes(){
    $this->Teacher->name = $this->Request->input('name');
    $this->Teacher->f_name = $this->Request->input('f_name');
    $this->Teacher->husband_name = $this->Request->input('husband_name');
    $this->Teacher->subject = $this->Request->input('subject');
    $this->Teacher->gender = $this->Request->input('gender');
    $this->Teacher->email = $this->Request->input('email');
    $this->Teacher->qualification = $this->Request->input('qualification');
    $this->Teacher->relegion = $this->Request->input('relegion');
    $this->Teacher->salary = $this->Request->input('salary');
    $this->Teacher->address = $this->Request->input('address');
    $this->Teacher->phone = $this->Request->input('phone');
  }


  protected function SaveImage(){
    $file = $this->Request->file('img');
    Storage::delete($this->Teacher->image_dir);
    $extension = $file->getClientOriginalExtension();
    Storage::disk('public')->put('teachers/'.$this->Teacher->id.'.'.$extension,  File::get($file));
//    $file = $this->Request->file('img')->storePubliclyAs('images/teachers', $this->Teacher->id.'.'.$file->getClientOriginalExtension(), 'public');
    $this->Teacher->image_dir = 'public/teachers/'.$this->Teacher->id.'.'.$extension;
    $this->Teacher->image_url = 'teacher/image/'.$this->Teacher->id;
  }

}
