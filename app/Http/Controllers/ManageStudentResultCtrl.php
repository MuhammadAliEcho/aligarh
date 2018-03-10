<?php

namespace App\Http\Controllers;

use Request;
use App\Http\Requests;
use App\Exam;
use App\Classe;
use App\Subject;
use App\Student;
use App\StudentResult;
use Carbon\Carbon;
use DB;
use Auth;

class ManageStudentResultCtrl extends Controller
{

	//  protected $Routes;
	protected $data, $Result, $Request, $Input;

	public function __Construct($Routes, $Request){
		$this->data['root'] = $Routes;
		$this->Request = $Request;
		$this->Input = $Request->input();
	}

	public function Index(){
		$this->data['exams'] = Exam::all();
		$this->data['classes'] = Classe::select('id', 'name')->get();
		foreach ($this->data['classes'] as $key => $class) {
			$this->data['subjects']['class_'.$class->id] = Subject::select('name', 'id')->where(['class_id' => $class->id])->get();
		}
		return view('manage_result', $this->data);
	}

	public function MakeResult(){

		$this->validate($this->Request, [
	        'exam'  	=>  'required',
	        'class'  	=>  'required|numeric',
	        'subject'  	=>  'required',
    	]);

//		$dbdate =	Carbon::createFromFormat('d/m/Y', $this->Request->input('date'))->toDateString();

		$this->data['selected_exam'] = Exam::find($this->Request->input('exam'));
		$this->data['selected_class'] = Classe::find($this->Request->input('class'));
		$this->data['selected_subject'] = Subject::find($this->Request->input('subject'));
		$this->data['students']	=	Student::select('id', 'name', 'gr_no')->where(['class_id' => $this->Input['class']])->get();

		foreach ($this->data['students'] as $k => $row) {
			$this->data['result'][$row->id] =	StudentResult::select('id as result_id', 'obtain_marks', 'remarks')->where(['student_id' => $row->id, 'exam_id' => $this->Input['exam']])->first();
		}

		$this->data['input'] = $this->Request->input();

		return $this->Index();

	}

	public function UpdateResult(){
		$this->validate($this->Request, [
			'exam'  	=>  'required',
			'subject'  	=>  'required',
			'total_marks'  	=>  'required',
		]);
		// $dbdate =	Carbon::createFromFormat('d/m/Y', $this->Request->input('date'))->toDateString();
		foreach($this->Input['student'] as $k => $stdnt) {
			$StudentResult = StudentResult::firstOrnew([
														'exam_id' => $this->Input['exam'],
														'student_id' => $k,
														'subject_id' => $this->Input['subject'],
														]);
			$StudentResult->total_marks = $this->Input['total_marks'];
			$StudentResult->obtain_marks = $stdnt['obtain_marks'];
			$StudentResult->remarks = $stdnt['remarks'];
			$StudentResult->user_id	=	Auth::user()->id;
			$StudentResult->save();
		}
		return redirect('manage-result')->with([
									'toastrmsg' => [
										'type' => 'success', 
										'title'  =>  'Student Results',
										'msg' =>  'Update Results Successfull'
									]
								]); 
	}

	public function ResultReport(){
		$this->validate($this->Request, [
			'exam'  	=>  'required',
			'class'  	=>  'required',
		]);

		$this->data['students']	=	Student::where(['class_id' => $this->Input['class']])->get();

		foreach ($this->data['students'] as $k => $row) {
			$this->data['result'][$row->id] =	StudentResult::where(['student_id' => $row->id, 'exam_id' => $this->Input['exam']])->get();
		}

		$this->data['input'] = $this->Request->input();
		$this->data['selected_exam'] = Exam::find($this->Request->input('exam'));
		$this->data['selected_class'] = Classe::find($this->Request->input('class'));
/*		$this->data['exam'] = Exam::find($this->Request->input('exam'));
		$this->data['class'] = Classe::find($this->Request->input('class'));*/

		return $this->Index();
	}


}
