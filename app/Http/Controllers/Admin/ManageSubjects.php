<?php

namespace App\Http\Controllers\Admin;

use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Teacher;
use App\Classe;
use App\Subject;
use DB;
use Auth;
use App\Http\Controllers\Controller;

class ManageSubjects extends Controller
{
	public function GetSubject(){

		$data['teachers'] = Teacher::select('name', 'id')->get();
		$data['classes'] = Classe::select('name', 'id')->get();
		
		foreach ($data['classes'] as $key => $class) {
		$data['subjects']['class_'.$class->id] = DB::table('subjects')
			->leftjoin('teachers', 'subjects.teacher_id', '=', 'teachers.id')
			->select('subjects.name', 'subjects.book', 'subjects.id', 'subjects.examinable', 'teachers.name AS teacher_name')
			->where('subjects.class_id', '=', $class->id)
			->get();
		}

		return view('admin.subjects', $data);

	}

	public function EditSubject($id){
		if(Subject::where('id', $id)->count() == 0){
		return  redirect('manage-subjects')->with([
				'toastrmsg' => [
					'type' => 'warning', 
					'title'  =>  '# Invalid URL',
					'msg' =>  'Do Not write hard URL\'s'
					]
			]);
		}
		$data['classes'] = Classe::select('name', 'id')->get();
		$data['teachers'] = Teacher::select('name', 'id')->get();
		$data['subject'] = Subject::find($id);

		return view('admin.edit_subject', $data);
	}

	public function AddSubject(Request $request){

		$this->PostValidate($request);
		$Subjects = new Subject;
		$this->SetAttributes($Subjects, $request);
		$Subjects->created_by = Auth::user()->id;
		$Subjects->save();

		return redirect('manage-subjects')->with([
				'toastrmsg' => [
					'type' => 'success', 
					'title'  =>  'Subjects Registration',
					'msg' =>  'Registration Successfull'
					]
			]);

	}

	public function PostEditSubject(Request $request, $id){

		$this->PostValidate($request);

		if(Subject::where('id', $id)->count() == 0){
		return  redirect('manage-subjects')->with([
				'toastrmsg' => [
					'type' => 'warning',
					'title'  =>  '# Invalid URL',
					'msg' =>  'Do Not write hard URL\'s'
					]
			]);
		}

		$Subjects = Subject::find($id);

		$this->SetAttributes($Subjects, $request);
		$Subjects->updated_by = Auth::user()->id;
		$Subjects->save();

		return redirect('manage-subjects')->with([
				'toastrmsg' => [
					'type' => 'success',
					'title'  =>  'Subject Registration',
					'msg' =>  'Save Changes Successfull'
					]
			]);
	}

	protected function PostValidate($request){
		$this->validate($request, [
				'name'  =>  'required',
				'book'  =>  'required',
//        'teacher' =>  'required',
				'class' =>  'required'
		]);
	}

	protected function SetAttributes($Subjects, $request){
		$Subjects->name = $request->input('name');
		$Subjects->book = $request->input('book');
		$Subjects->examinable	=	$request->input('examinable');
		$Subjects->teacher_id = $request->input('teacher');
		$Subjects->class_id = $request->input('class');
	}

}
