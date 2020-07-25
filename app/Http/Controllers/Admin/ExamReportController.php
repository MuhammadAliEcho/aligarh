<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\SubjectResultAttribute;
use Illuminate\Http\Request;
use App\AcademicSessionHistory;
use App\ExamRemark;
use App\Classe;
use App\Exam;
use App\AcademicSession;
use App\Grade;
use App\Subject;
use App\Student;
use App\StudentAttendance;
use Auth;
use Validator;

class ExamReportController extends Controller
{

	protected $data, $Request;

	public function __Construct($Routes, $request){
		$this->data['root'] = $Routes;
		$this->Request 	=	$request;
	}

	public function Index(){
		$this->data['exams'] = Exam::Active()->with('AcademicSession')->CurrentSession()->get();
		$this->data['classes'] = Classe::select('id', 'name')->get();
		$this->data['Subjects']	=	Subject::select('id', 'name', 'class_id')->get();
		return view('admin.exam_report', $this->data);
	}

	public function FindStudent(){
		if ($this->Request->ajax()) {
			$students = Student::select('students.id', 'students.gr_no', 'students.name')
								->where('students.gr_no', 'LIKE', '%'.$this->Request->input('q').'%')
								->orwhere('students.name', 'LIKE', '%'.$this->Request->input('q').'%')
								->join('academic_session_history', function($join)
									{
										$join->on('students.id', '=', 'academic_session_history.student_id')
											->where('academic_session_history.academic_session_id', Auth::user()->academic_session);
									})
								->get();
			foreach ($students as $k=>$student) {
				$data[$k]['id'] = $student->id;
				$data[$k]['text'] = $student->gr_no.' | '.$student->name;
/*				$data[$k]['htm1'] = '<span class="text-danger">';
				$data[$k]['htm2'] = '</span>';*/
			}
			return response(isset($data)? $data : [0 => ['text' => 'No Data Available']]);
		}
		return abort(404);
	}


	public function GetExamTabulation(Request $request){
		$this->validate($request, [
			'exam'		=>	'required',
			'class'    	=>	'required',
		]);

		$this->data['selected_exam'] = Exam::Active()->CurrentSession()->with('AcademicSession')->findOrFail($request->input('exam'));
		$this->data['selected_class'] = Classe::findOrFail($request->input('class'));		

		$this->data['grades']	=	Grade::all();

		$this->data['subject_result_attributes'] = SubjectResultAttribute::where([
														'exam_id'	=>	$this->data['selected_exam']->id,
														'class_id'	=>	$this->data['selected_class']->id
													])->with('Subject')->get();

		$this->data['transcripts'] = ExamRemark::where([
			'exam_id'	=>	$this->data['selected_exam']->id,
			'class_id'	=>	$this->data['selected_class']->id,
		])->with(['Student'	=>	function($qry){
			$qry->select('id', 'name', 'gr_no', 'father_name');
			$qry->with(['StudentAttendance' => function ($qry){
				$qry->select('id', 'student_id', 'status', 'date');
				$qry->GetAttendanceForExam($this->data['selected_exam']);
			}]);
		}])->with(['StudentResult'	=>	function($qry){
			$qry->with('Subject')->with('SubjectResultAttribute')->orderBy('subject_result_attribute_id');
		}])->get();


//		dd($this->data['selected_exam']);

		return view('admin.printable.exam_tabulation_sheet', $this->data);

	}

	public function AwardList(Request $request){

		$this->validate($request, [
			'exam'	=>	'required',
			'class'	=>	'required',
			'subject'	=>	'required',
		]);

		$this->data['selected_exam'] = Exam::Active()->CurrentSession()->with('AcademicSession')->findOrFail($request->input('exam'));
		$this->data['selected_class'] = Classe::findOrFail($request->input('class'));
		$this->data['selected_subject'] = Subject::findOrFail($request->input('subject'));
		$this->data['grades']	=	Grade::all();

		$this->data['result_attribute']	=	SubjectResultAttribute::where([
											'exam_id'	=>	$request->input('exam'),
											'subject_id'	=>	$request->input('subject'),
											'class_id'	=>	$request->input('class'),
										])->with(['StudentResult' => function($qry){
											$qry->with('Student');
										}])->firstOrFail();

		return view('admin.printable.exam_award_list', $this->data);

	}

	public function AverageResult(Request $request){
		
		$this->validate($request,[
			'exam'			=>	'required',
			'class'			=>	'required'
		]);

		$exam_category = [
			1	=>	[1,2],
			2	=>	[3,4]
		];

		if ($request->input('exam') == 1) {
			$this->data['exam_title']	=	'1st Assessment / Half Year';
		} else {
			$this->data['exam_title']	=	'2nd Assessment / Final Year';
		}

		$this->data['selected_exams']	=	Exam::wherein('category_id', $exam_category[$request->input('exam')])->CurrentSession()->with('AcademicSession')->get();
		$this->data['selected_class']	=	Classe::findOrFail($request->input('class'));
		$this->data['grades']			=	Grade::all();

		foreach ($this->data['selected_exams'] as $key => $value) {
			$this->data['results'][] = ExamRemark::where([
				'exam_id'	=>	$value->id,
				'class_id'	=>	$this->data['selected_class']->id,
			])->with(['Student'	=>	function($qry){
				$qry->select('id', 'name', 'gr_no', 'father_name');
			}])->with(['StudentResult'	=>	function($qry){
				$qry->with('Subject')->with('SubjectResultAttribute')->orderBy('subject_result_attribute_id');
			}])->orderBy('student_id')->get();
		}

		return view('admin.printable.exam_average_result', $this->data);

	}

	public function ResultTranscript(Request $request){

		$this->validate($request, [
			'exam'			=>	'required',
			'student_id'	=>	'required'
		]);


		$exam_category = [
			1	=>	[1,2],
			2	=>	[3,4]
		];

		if ($request->input('exam') == 1) {
			$this->data['exam_title']	=	'1st Assessment / Half Year';
		} else {
			$this->data['exam_title']	=	'2nd Assessment / Final Year';
		}

		$this->data['selected_exams']	=	Exam::wherein('category_id', $exam_category[$request->input('exam')])->CurrentSession()->with('AcademicSession')->get();

		if($this->data['selected_exams']->count() !== 2){
			return redirect('exam-reports')->with([
				'toastrmsg' => [
					'type' => 'error', 
					'title'  =>  'Result Reports',
					'msg' =>  'Combine exam not found'
				]
			]);
		}
		
		$this->data['student']			=	Student::findOrFail($request->input('student_id'));
		$this->data['attendance']['total']		=	StudentAttendance::select('id', 'student_id', 'status', 'date')
												->where('student_id', $this->data['student']->id)
												->whereBetween('date', [$this->data['selected_exams'][0]->getOriginal('start_date'), $this->data['selected_exams'][1]->getOriginal('end_date')])
//												->whereBetween('date', ['2018-04-01', '2019-03-31'])
												->get();
		$this->data['attendance']['first_exam']	=	StudentAttendance::select('id', 'student_id', 'status', 'date')
													->where('student_id', $this->data['student']->id)
													->whereBetween('date', [$this->data['selected_exams'][0]->getOriginal('start_date'), $this->data['selected_exams'][0]->getOriginal('end_date')])
													->get();
		$this->data['attendance']['second_exam']	=	StudentAttendance::select('id', 'student_id', 'status', 'date')
														->where('student_id', $this->data['student']->id)
														->whereBetween('date', [$this->data['selected_exams'][1]->getOriginal('start_date'), $this->data['selected_exams'][1]->getOriginal('end_date')])
														->get();
		$AcademicSessionHistory			=	AcademicSessionHistory::where('student_id', $this->data['student']->id)->CurrentSession()->with('classe')->first();

		if($AcademicSessionHistory == null){
			return redirect('exam-reports')->with([
				'toastrmsg' => [
					'type' => 'error', 
					'title'  =>  'Result Reports',
					'msg' =>  'Student not found on Session'
				]
			]);
		}

		$this->data['student_class']	=	$AcademicSessionHistory->classe;
		foreach ($this->data['selected_exams'] as $key => $value) {
			$this->data['results'][]			=	ExamRemark::where([
													'exam_id'		=>	$value->id,
													'student_id'	=>	$this->data['student']->id,
												])->with(['StudentResult'	=>	function($qry){
													$qry->with('Subject')->with('SubjectResultAttribute');
													}]
												)->with('Classe')->first();
		}

//		$this->data['selected_class']	=	Classe::findOrFail($request->input('class'));
		if($this->data['results'][0] == null && $this->data['results'][1] == null){
			return redirect('exam-reports')->with([
				'toastrmsg' => [
					'type' => 'warning', 
					'title'  =>  'Result Reports',
					'msg' =>  'Data Not Found'
				]
			]);
		}
		$this->data['grades']			=	Grade::all();

		return view('admin.printable.exam_transcript', $this->data);

	}

	public function UpdateRank(){

		if($this->Request->ajax()){

			$validator = Validator::make($this->Request->all(), [
				'rank' => 'required',
			]);

			if ($validator->fails()) {
				return  [
					'type'	=> 'error', 
					'title'	=>  'Student Results',
					'msg'	=>  'Something is wrong!'
				];
			}

			foreach ($this->Request->input('rank') as $id => $rank) {
				ExamRemark::findOrFail($id)->update(['rank'	=>	$rank]);
			}

			return	[
				'type'	=> 'success', 
				'title'	=>  'Student Results',
				'msg'	=>  'Update Results Successfull'
			];
		}
	
		return redirect('exam-reports')->with([
									'toastrmsg' => [
										'type'	=> 'warning', 
										'title'	=>  'Student Results',
										'msg'	=>  'Something is wrong!'
									]
								]);
	}


}
