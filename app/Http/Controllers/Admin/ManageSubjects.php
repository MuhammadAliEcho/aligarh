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

	//  protected $Routes;
	protected $data, $Classes, $Subjects, $Request;

	public function __Construct($Routes){
		$this->data['root'] = $Routes;
	}

	public function GetSubject(){

		$this->data['teachers'] = Teacher::select('name', 'id')->get();
		$this->data['classes'] = Classe::select('name', 'id')->get();
		
		foreach ($this->data['classes'] as $key => $class) {
		$this->data['subjects']['class_'.$class->id] = DB::table('subjects')
			->leftjoin('teachers', 'subjects.teacher_id', '=', 'teachers.id')
			->select('subjects.name', 'subjects.book', 'subjects.id', 'subjects.examinable', 'teachers.name AS teacher_name')
			->where('subjects.class_id', '=', $class->id)
			->get();
		}

		return view('admin.subjects', $this->data);

	}

	public function EditSubject(){
		if(Subject::where('id', $this->data['root']['option'])->count() == 0){
		return  redirect('manage-subjects')->with([
				'toastrmsg' => [
					'type' => 'warning', 
					'title'  =>  '# Invalid URL',
					'msg' =>  'Do Not write hard URL\'s'
					]
			]);
		}
		$this->data['classes'] = Classe::select('name', 'id')->get();
		$this->data['teachers'] = Teacher::select('name', 'id')->get();
		$this->data['subject'] = Subject::find($this->data['root']['option']);

		return view('admin.edit_subject', $this->data);
	}

	public function AddSubject(Request $request){

		$this->Request = $request;
		$this->PostValidate();
		$this->Subjects = new Subject;
		$this->SetAttributes();
		$this->Subjects->created_by = Auth::user()->id;
		$this->Subjects->save();

		return redirect('manage-subjects')->with([
				'toastrmsg' => [
					'type' => 'success', 
					'title'  =>  'Subjects Registration',
					'msg' =>  'Registration Successfull'
					]
			]);

	}

	public function PostEditSubject(Request $request){

		$this->Request = $request;
		$this->PostValidate();

		if(Subject::where('id', $this->data['root']['option'])->count() == 0){
		return  redirect('manage-subjects')->with([
				'toastrmsg' => [
					'type' => 'warning',
					'title'  =>  '# Invalid URL',
					'msg' =>  'Do Not write hard URL\'s'
					]
			]);
		}

		$this->Subjects = Subject::find($this->data['root']['option']);

		$this->SetAttributes();
		$this->Subjects->updated_by = Auth::user()->id;
		$this->Subjects->save();

		return redirect('manage-subjects')->with([
				'toastrmsg' => [
					'type' => 'success',
					'title'  =>  'Subject Registration',
					'msg' =>  'Save Changes Successfull'
					]
			]);
	}

	protected function PostValidate(){
		$this->validate($this->Request, [
				'name'  =>  'required',
				'book'  =>  'required',
//        'teacher' =>  'required',
				'class' =>  'required'
		]);
	}

	protected function SetAttributes(){
		$this->Subjects->name = $this->Request->input('name');
		$this->Subjects->book = $this->Request->input('book');
		$this->Subjects->examinable	=	$this->Request->input('examinable');
		$this->Subjects->teacher_id = $this->Request->input('teacher');
		$this->Subjects->class_id = $this->Request->input('class');
	}

}
