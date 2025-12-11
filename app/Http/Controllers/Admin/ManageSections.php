<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Model\Teacher;
use App\Model\Classe;
use App\Model\Section;
use DB;
use Auth;
use App\Http\Controllers\Controller;

class ManageSections extends Controller
{
  public function GetSections(){

    $data['teachers'] = Teacher::select('name', 'id')->get();
    $data['classes'] = Classe::select('name', 'id')->orderBy('numeric_name')->with(['Section' => function($qry){
      $qry->with(['Students' => function($qry){
        $qry->Active();
      }])->with('Teacher');
    }])->get();

    return view('admin.sections', $data);

  }

  public function EditSection($id){
    if(Section::where('id', $id)->count() == 0){
    return  redirect('manage-sections')->with([
        'toastrmsg' => [
          'type' => 'warning', 
          'title'  =>  '# Invalid URL',
          'msg' =>  __('modules.common_url_error')
          ]
      ]);
    }

    $data['classes'] = Classe::select('name', 'id')->get();
    $data['teachers'] = Teacher::select('name', 'id')->get();
    $data['section'] = Section::find($id);

    return view('admin.edit_section', $data);
  }

  public function AddSection(Request $request){

    $this->PostValidate($request);
    $Sections = new Section;
    $this->SetAttributes($Sections, $request);
    $Sections->created_by = Auth::user()->id;
    $Sections->save();

    return redirect('manage-sections')->with([
        'toastrmsg' => [
          'type' => 'success', 
          'title'  =>  'Section Registration',
          'msg' =>  __('modules.common_register_success')
          ]
      ]);

  }

  public function PostEditSection(Request $request, $id){

    $this->PostValidate($request);

    if(Section::where('id', $id)->count() == 0){
    return  redirect('manage-sections')->with([
        'toastrmsg' => [
          'type' => 'warning', 
          'title'  =>  '# Invalid URL',
          'msg' =>  __('modules.common_url_error')
          ]
      ]);
    }

    $Sections = Section::find($id);

    $this->SetAttributes($Sections, $request);
    $Sections->updated_by = Auth::user()->id;
    $Sections->save();

    return redirect('manage-sections')->with([
        'toastrmsg' => [
          'type' => 'success',
          'title'  =>  'Section Registration',
          'msg' =>  __('modules.common_save_success')
          ]
      ]);
  }

  protected function PostValidate($request){
    $this->validate($request, [
        'name'  =>  'required',
        'nick_name'  =>  'required',
        'capacity'  =>  'required:number',
/*        'teacher' =>  'required',*/
        'class' =>  'required'
    ]);
  }

  protected function SetAttributes($Sections, $request){
    $Sections->name = $request->input('name');
    $Sections->nick_name = $request->input('nick_name');
    $Sections->capacity = $request->input('capacity');
    $Sections->teacher_id = $request->input('teacher');
    $Sections->class_id = $request->input('class');
  }

}
