<?php

namespace App\Http\Controllers\Admin;

use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Model\Teacher;
use App\Model\Classe;
use App\Model\Subject;
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
					'title'  =>  __('modules.subjects_invalid_url_title'),
					'msg' =>  __('modules.common_url_error')
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
					'title'  =>  __('modules.subjects_registration_title'),
					'msg' =>  __('modules.common_register_success')
					]
			]);

	}

	public function PostEditSubject(Request $request, $id){

		$this->PostValidate($request);

		if(Subject::where('id', $id)->count() == 0){
		return  redirect('manage-subjects')->with([
				'toastrmsg' => [
					'type' => 'warning',
					'title'  =>  __('modules.subjects_invalid_url_title'),
					'msg' =>  __('modules.common_url_error')
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
					'title'  =>  __('modules.subject_registration_title'),
					'msg' =>  __('modules.common_save_success')
					]
			]);
	}

	protected function PostValidate($request){
		$this->validate($request, [
				'name'  =>  'required',
				'book'  =>  'required',
//        'teacher' =>  'required',
				'class' =>  'required'
		], [
				'name.required'  =>  __('validation.name_required'),
				'book.required'  =>  __('validation.book_required'),
				'class.required' =>  __('validation.class_required'),
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
