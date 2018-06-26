<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Request;
use App\Http\Requests;
use App\Exam;
use App\Classe;
use App\Subject;
use App\Student;
use App\StudentResult;
use App\ExamRemark;
use App\SubjectResultAttribute;
use Carbon\Carbon;
use DB;
use Auth;
use Validator;

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
		$this->data['exams'] = Exam::Active()->get();
		$this->data['classes'] = Classe::select('id', 'name')->get();
		foreach ($this->data['classes'] as $key => $class) {
			$this->data['subjects']['class_'.$class->id] = Subject::select('name', 'id')->where(['class_id' => $class->id])->get();
		}
		return view('admin.manage_result', $this->data);
	}

	public function MakeResult(){

		$this->validate($this->Request, [
			'exam'  	=>  'required',
			'class'  	=>  'required|numeric',
			'subject'  	=>  'required',
		]);

//		$dbdate =	Carbon::createFromFormat('d/m/Y', $this->Request->input('date'))->toDateString();

		$this->data['selected_exam'] = Exam::Active()->findOrFail($this->Request->input('exam'));
		$this->data['selected_class'] = Classe::find($this->Request->input('class'));
		$this->data['selected_subject'] = Subject::find($this->Request->input('subject'));
		$this->data['result_attribute']	=	SubjectResultAttribute::where([
													'exam_id'	=>	$this->Input['exam'],
													'subject_id'	=>	$this->Input['subject']
												])->first();

		$this->data['students']	=	Student::select('id', 'name', 'gr_no')->where(['class_id' => $this->Input['class']])->CurrentSession()->Active();

		if ($this->data['result_attribute']) {
			$this->data['students']->with(['StudentSubjectResult' => function($query){
				$query->where([
					'subject_result_attribute_id'	=>	$this->data['result_attribute']->id
				]);
			}]);
		}

		$this->data['students']	=	$this->data['students']->get();

		$this->data['input'] = $this->Request->input();

		return $this->Index();

	}

	public function UpdateResult(){
		$this->validate($this->Request, [
			'exam'  	=>  'required',
			'subject'  	=>  'required',
			'total_marks'  	=>  'required',
			'students'  	=>  'required',
			'attributes'  	=>  'required',
		]);

		// $dbdate =	Carbon::createFromFormat('d/m/Y', $this->Request->input('date'))->toDateString();

		$result_attribute = SubjectResultAttribute::updateOrCreate([
								'subject_id'	=>	$this->Request->input('subject'),
								'class_id'		=>	$this->Request->input('class'),
								'exam_id'		=>	$this->Request->input('exam'),
							],
							[
								'total_marks'		=>	$this->Request->input('total_marks'),
								'attributes'		=>	$this->Request->input('attributes'),
							]);

		foreach($this->Input['students'] as $k => $student) {

			$ExamRemark 	=	ExamRemark::firstOrCreate([
					'exam_id'	=>	$this->Request->input('exam'),
					'class_id'	=>	$this->Request->input('class'),
					'student_id'	=>	$k,
				]
			);

			$obtain_marks	=	collect($student['obtain_marks']);
			StudentResult::updateOrCreate([
				'subject_result_attribute_id' => $result_attribute->id,
				'student_id' => $k,
				'exam_remark_id' => $ExamRemark->id,
			],
			[
				'subject_id'	=>	$this->Request->input('subject'),
				'exam_id'		=>	$this->Request->input('exam'),
				'obtain_marks'	=>	$this->MakeObtainMarks($student['obtain_marks']),
				'total_obtain_marks'	=>	$obtain_marks->sum('marks'),
			]);
		}
		return redirect('manage-result')->with([
									'toastrmsg' => [
										'type'	=> 'success', 
										'title'	=>  'Student Results',
										'msg'	=>  'Update Results Successfull'
									]
								]); 
	}

	protected function MakeObtainMarks($obtain_marks){
		foreach ($obtain_marks as $key => $value) {
			$array[]	=	[
				'name'	=>	$value['name'],
				'marks'	=>	$value['marks'],
				'attendance'	=>	($value['attendance'] == 'true')? true : false
			];
		}
		return $array;
	}

	public function ResultAttributes(){
		$this->validate($this->Request, [
			'exam'  	=>  'required',
			'class'  	=>  'required',
		]);

		$this->data['input'] = $this->Request->input();
		$this->data['selected_exam'] = Exam::Active()->findOrFail($this->Request->input('exam'));
		$this->data['selected_class'] = Classe::findOrFail($this->Request->input('class'));

		$this->data['subject_result']	=	SubjectResultAttribute::where(['exam_id' => $this->data['selected_exam']->id, 'class_id' => $this->data['selected_class']->id])->with('Subject')->get();

		return $this->Index();
	}

	public function MakeTranscript(){

		$this->validate($this->Request, [
			'exam'  	=>  'required',
			'class'  	=>  'required',
		]);

		$this->data['input'] = $this->Request->input();
		$this->data['selected_exam'] = Exam::Active()->findOrFail($this->Request->input('exam'));
		$this->data['selected_class'] = Classe::findOrFail($this->Request->input('class'));

		$this->data['transcripts'] = ExamRemark::where([
			'exam_id'	=>	$this->data['selected_exam']->id,
			'class_id'	=>	$this->data['selected_class']->id,
		])->with(['Student'	=>	function($qry){
			$qry->select('id', 'name', 'gr_no', 'father_name');
		}])->with(['StudentResult'	=>	function($qry){
			$qry->with('Subject')->with('SubjectResultAttribute');
		}])->get();

		return view('admin.make_transcript', $this->data);
	}

	public function SaveTranscript(){

		if($this->Request->ajax()){

			$validator = Validator::make($this->Request->all(), [
				'id' => 'required',
			]);

			if ($validator->fails()) {
				return  [
					'type'	=> 'error', 
					'title'	=>  'Student Results',
					'msg'	=>  'Something is wrong!'
				];
			}

			$ExamRemark = ExamRemark::findOrFail($this->Request->input('id'));
			$ExamRemark->remarks = $this->Request->input('remarks');
			$ExamRemark->save();
		
			return	[
				'type'	=> 'success', 
				'title'	=>  'Student Results',
				'msg'	=>  'Update Results Successfull'
			];
		}
	
		return redirect('manage-result')->with([
									'toastrmsg' => [
										'type'	=> 'warning', 
										'title'	=>  'Student Results',
										'msg'	=>  'Something is wrong!'
									]
								]);

	}

}
