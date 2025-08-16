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
	public function Index(){
		$data['exams'] = Exam::Active()->with('AcademicSession')->CurrentSession()->get();
		$data['classes'] = Classe::select('id', 'name')->get();
		$data['Subjects']	=	Subject::select('id', 'name', 'class_id')->get();
		return view('admin.exam_report', $data);
	}

	public function FindStudent(Request $request){
		if ($request->ajax()) {
			$students = Student::select('students.id', 'students.gr_no', 'students.name')
								->where('students.gr_no', 'LIKE', '%'.$request->input('q').'%')
								->orwhere('students.name', 'LIKE', '%'.$request->input('q').'%')
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

		$data['selected_exam'] = Exam::Active()->CurrentSession()->with('AcademicSession')->findOrFail($request->input('exam'));
		$data['selected_class'] = Classe::findOrFail($request->input('class'));		

		$data['grades']	=	Grade::all();

		$data['subject_result_attributes'] = SubjectResultAttribute::where([
														'exam_id'	=>	$data['selected_exam']->id,
														'class_id'	=>	$data['selected_class']->id
													])->with('Subject')->get();

		$data['transcripts'] = ExamRemark::where([
			'exam_id'	=>	$data['selected_exam']->id,
			'class_id'	=>	$data['selected_class']->id,
		])->with(['Student'	=>	function($qry) use ($data){
			$qry->select('id', 'name', 'gr_no', 'father_name');
			$qry->with(['StudentAttendance' => function ($qry) use ($data){
				$qry->select('id', 'student_id', 'status', 'date');
				$qry->GetAttendanceForExam($data['selected_exam']);
			}]);
		}])->with(['StudentResult'	=>	function($qry){
			$qry->with('Subject')->with('SubjectResultAttribute')->orderBy('subject_result_attribute_id');
		}])->get();


//		dd($data['selected_exam']);

		return view('admin.printable.exam_tabulation_sheet', $data);

	}

	public function AwardList(Request $request){

		$this->validate($request, [
			'exam'	=>	'required',
			'class'	=>	'required',
			'subject'	=>	'required',
		]);

		$data['selected_exam'] = Exam::Active()->CurrentSession()->with('AcademicSession')->findOrFail($request->input('exam'));
		$data['selected_class'] = Classe::findOrFail($request->input('class'));
		$data['selected_subject'] = Subject::findOrFail($request->input('subject'));
		$data['grades']	=	Grade::all();

		$data['result_attribute']	=	SubjectResultAttribute::where([
											'exam_id'	=>	$request->input('exam'),
											'subject_id'	=>	$request->input('subject'),
											'class_id'	=>	$request->input('class'),
										])->with(['StudentResult' => function($qry){
											$qry->with('Student');
										}])->firstOrFail();

		return view('admin.printable.exam_award_list', $data);

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
			$data['exam_title']	=	'1st Assessment / Half Year';
		} else {
			$data['exam_title']	=	'2nd Assessment / Final Year';
		}

		$data['selected_exams']	=	Exam::wherein('category_id', $exam_category[$request->input('exam')])->CurrentSession()->with('AcademicSession')->get();
		$data['selected_class']	=	Classe::findOrFail($request->input('class'));
		$data['grades']			=	Grade::all();

		foreach ($data['selected_exams'] as $key => $value) {
			$data['results'][] = ExamRemark::where([
				'exam_id'	=>	$value->id,
				'class_id'	=>	$data['selected_class']->id,
			])->with(['Student'	=>	function($qry){
				$qry->select('id', 'name', 'gr_no', 'father_name');
			}])->with(['StudentResult'	=>	function($qry){
				$qry->with('Subject')->with('SubjectResultAttribute')->orderBy('subject_result_attribute_id');
			}])->orderBy('student_id')->get();
		}

		return view('admin.printable.exam_average_result', $data);

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
			$data['exam_title']	=	'1st Assessment / Half Year';
		} else {
			$data['exam_title']	=	'2nd Assessment / Final Year';
		}

		$data['selected_exams']	=	Exam::wherein('category_id', $exam_category[$request->input('exam')])->CurrentSession()->with('AcademicSession')->get();

		if($data['selected_exams']->count() !== 2){
			return redirect('exam-reports')->with([
				'toastrmsg' => [
					'type' => 'error', 
					'title'  =>  'Result Reports',
					'msg' =>  'Combine exam not found'
				]
			]);
		}
		
		$data['student']			=	Student::findOrFail($request->input('student_id'));
		$data['attendance']['total']		=	StudentAttendance::select('id', 'student_id', 'status', 'date')
												->where('student_id', $data['student']->id)
												->whereBetween('date', [$data['selected_exams'][0]->getRawOriginal('start_date'), $data['selected_exams'][1]->getRawOriginal('end_date')])
//												->whereBetween('date', ['2018-04-01', '2019-03-31'])
												->get();
		$data['attendance']['first_exam']	=	StudentAttendance::select('id', 'student_id', 'status', 'date')
													->where('student_id', $data['student']->id)
													->whereBetween('date', [$data['selected_exams'][0]->getRawOriginal('start_date'), $data['selected_exams'][0]->getRawOriginal('end_date')])
													->get();
		$data['attendance']['second_exam']	=	StudentAttendance::select('id', 'student_id', 'status', 'date')
														->where('student_id', $data['student']->id)
														->whereBetween('date', [$data['selected_exams'][1]->getRawOriginal('start_date'), $data['selected_exams'][1]->getRawOriginal('end_date')])
														->get();
		$AcademicSessionHistory			=	AcademicSessionHistory::where('student_id', $data['student']->id)->CurrentSession()->with('classe')->first();

		if($AcademicSessionHistory == null){
			return redirect('exam-reports')->with([
				'toastrmsg' => [
					'type' => 'error', 
					'title'  =>  'Result Reports',
					'msg' =>  'Student not found on Session'
				]
			]);
		}

		$data['student_class']	=	$AcademicSessionHistory->classe;
		foreach ($data['selected_exams'] as $key => $value) {
			$data['results'][]			=	ExamRemark::where([
													'exam_id'		=>	$value->id,
													'student_id'	=>	$data['student']->id,
												])->with(['StudentResult'	=>	function($qry){
													$qry->with('Subject')->with('SubjectResultAttribute');
													}]
												)->with('Classe')->first();
		}

//		$data['selected_class']	=	Classe::findOrFail($request->input('class'));
		if($data['results'][0] == null && $data['results'][1] == null){
			return redirect('exam-reports')->with([
				'toastrmsg' => [
					'type' => 'warning', 
					'title'  =>  'Result Reports',
					'msg' =>  'Data Not Found'
				]
			]);
		}
		$data['grades']			=	Grade::all();

		return view('admin.printable.exam_transcript', $data);

	}

	public function UpdateRank(Request $request){

		if($request->ajax()){

			$validator = Validator::make($request->all(), [
				'rank' => 'required',
			]);

			if ($validator->fails()) {
				return  [
					'type'	=> 'error', 
					'title'	=>  'Student Results',
					'msg'	=>  'Something is wrong!'
				];
			}

			foreach ($request->input('rank') as $id => $rank) {
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
