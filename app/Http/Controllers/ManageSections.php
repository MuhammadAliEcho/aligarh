<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Teacher;
use App\Classe;
use App\Section;
use DB;
use Auth;

class ManageSections extends Controller
{

  //  protected $Routes;
  protected $data, $Classes, $Sections, $Request;

  public function __Construct($Routes){
    $this->data['root'] = $Routes;
  }

  public function GetSections(){

    $this->data['teachers'] = Teacher::select('name', 'id')->get();
    $this->data['classes'] = Classe::select('name', 'id')->orderBy('numeric_name')->get();

    return view('sections', $this->data);

  }

  public function EditSection(){
    if(Section::where('id', $this->data['root']['option'])->count() == 0){
    return  redirect('manage-sections')->with([
        'toastrmsg' => [
          'type' => 'warning', 
          'title'  =>  '# Invalid URL',
          'msg' =>  'Do Not write hard URL\'s'
          ]
      ]);
    }

    $this->data['classes'] = Classe::select('name', 'id')->get();
    $this->data['teachers'] = Teacher::select('name', 'id')->get();
    $this->data['section'] = Section::find($this->data['root']['option']);

    return view('edit_section', $this->data);
  }

  public function AddSection(Request $request){

    $this->Request = $request;
    $this->PostValidate();
    $this->Sections = new Section;
    $this->SetAttributes();
    $this->Sections->created_by = Auth::user()->id;
    $this->Sections->save();

    return redirect('manage-sections')->with([
        'toastrmsg' => [
          'type' => 'success', 
          'title'  =>  'Section Registration',
          'msg' =>  'Registration Successfull'
          ]
      ]);

  }

  public function PostEditSection(Request $request){

    $this->Request = $request;
    $this->PostValidate();

    if(Section::where('id', $this->data['root']['option'])->count() == 0){
    return  redirect('manage-sections')->with([
        'toastrmsg' => [
          'type' => 'warning', 
          'title'  =>  '# Invalid URL',
          'msg' =>  'Do Not write hard URL\'s'
          ]
      ]);
    }

    $this->Sections = Section::find($this->data['root']['option']);

    $this->SetAttributes();
    $this->Sections->updated_by = Auth::user()->id;
    $this->Sections->save();

    return redirect('manage-sections')->with([
        'toastrmsg' => [
          'type' => 'success',
          'title'  =>  'Section Registration',
          'msg' =>  'Save Changes Successfull'
          ]
      ]);
  }

  protected function PostValidate(){
    $this->validate($this->Request, [
        'name'  =>  'required',
        'nick_name'  =>  'required',
/*        'teacher' =>  'required',*/
        'class' =>  'required'
    ]);
  }

  protected function SetAttributes(){
    $this->Sections->name = $this->Request->input('name');
    $this->Sections->nick_name = $this->Request->input('nick_name');
    $this->Sections->teacher_id = $this->Request->input('teacher');
    $this->Sections->class_id = $this->Request->input('class');
  }

}
