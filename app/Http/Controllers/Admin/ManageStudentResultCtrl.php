<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
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
	public function Index(array $data = [], $job = ''){
		$data['exams'] = Exam::Active()->CurrentSession()->get();
		$data['classes'] = Classe::select('id', 'name')->get();
		foreach ($data['classes'] as $key => $class) {
			$data['subjects']['class_'.$class->id] = Subject::select('name', 'id')->where(['class_id' => $class->id])->Examinable()->get();
		}
		$data['root'] = $job;
		return view('admin.manage_result', $data);
	}

	public function MakeResult(Request $request){

		$this->validate($request, [
			'exam'  	=>  'required',
			'class'  	=>  'required|numeric',
			'subject'  	=>  'required',
		]);

		//$dbdate =	Carbon::createFromFormat('d/m/Y', $request->input('date'))->toDateString();

		$data['selected_exam'] = Exam::Active()->CurrentSession()->where('id', $request->input('exam'))->first();

		if($data['selected_exam'] == null){
			return redirect('manage-result')->withInput()->with([
				'toastrmsg' => [
								'type'	=> 'error',
								'title'	=>  'Student Results',
								'msg'	=>  'Exam not found in selected session'
							]
			]);
		}

		$data['selected_class'] = Classe::find($request->input('class'));
		$data['selected_subject'] = Subject::find($request->input('subject'));
		$data['result_attribute']	=	SubjectResultAttribute::where([
													'exam_id'	=>	$request['exam'],
													'subject_id'	=>	$request['subject']
												])->first();

		$data['students']	=	Student::select('id', 'name', 'gr_no')->where(['class_id' => $request['class']])->CurrentSession()->Active()->orderBy('name');

		if ($data['result_attribute']) {
			$data['students']->with(['StudentSubjectResult' => function($query) use ($data) {
				$query->where([
					'subject_result_attribute_id'	=>	$data['result_attribute']->id
				]);
			}]);
		}

		$data['students']	=	$data['students']->get();

		if($data['students']->isEmpty()){
			return redirect('manage-result')->withInput()->with([
				'toastrmsg' => [
								'type'	=> 'error',
								'title'	=>  'Student Results',
								'msg'	=>  'Students not found in selected session'
							]
			]);
		}

		$data['input'] = $request->input();
		$job = 'make';
		return $this->Index($data, $job);

	}

	public function UpdateResult(Request $request){
		$this->validate($request, [
			'exam'  	=>  'required',
			'subject'  	=>  'required',
			'total_marks'  	=>  'required',
			'students'  	=>  'required',
			'attributes'  	=>  'required',
		]);

		// $dbdate =	Carbon::createFromFormat('d/m/Y', $request->input('date'))->toDateString();

		$result_attribute = SubjectResultAttribute::updateOrCreate([
								'subject_id'	=>	$request->input('subject'),
								'class_id'		=>	$request->input('class'),
								'exam_id'		=>	$request->input('exam'),
							],
							[
								'total_marks'		=>	$request->input('total_marks'),
								'attributes'		=>	$request->input('attributes'),
							]);

		foreach($request['students'] as $k => $student) {

			$ExamRemark 	=	ExamRemark::firstOrCreate([
					'exam_id'	=>	$request->input('exam'),
					'class_id'	=>	$request->input('class'),
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
				'subject_id'	=>	$request->input('subject'),
				'exam_id'		=>	$request->input('exam'),
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

	public function RemoveResult($request){
		SubjectResultAttribute::findOrFail($request->input('SavedResultId'))->delete();
		StudentResult::where('subject_result_attribute_id', $request->input('SavedResultId'))->delete();
		return redirect('manage-result')->with([
									'toastrmsg' => [
										'type'	=> 'success', 
										'title'	=>  'Student Results',
										'msg'	=>  'Deleted Results Successfull'
									]
								]); 
	}

	public function ResultAttributes(Request $request){
		$this->validate($request, [
			'exam'  	=>  'required',
			'class'  	=>  'required',
		]);

		$data['input'] = $request->input();
		$data['selected_exam'] = Exam::Active()->CurrentSession()->where('id', $request->input('exam'))->first();

		if($data['selected_exam'] == null){
			return redirect('manage-result')->withInput()->with([
				'toastrmsg' => [
								'type'	=> 'error',
								'title'	=>  'Student Results',
								'msg'	=>  'Exam not found in selected session'
							]
			]);
		}

		$data['selected_class'] = Classe::findOrFail($request->input('class'));

		$data['subject_result']	=	SubjectResultAttribute::where(['exam_id' => $data['selected_exam']->id, 'class_id' => $data['selected_class']->id])->with('Subject')->get();

		$job = 'resultattributes';
		return $this->Index($data, $job);
	}

	public function MakeTranscript(Request $request){

		$this->validate($request, [
			'exam'  	=>  'required',
			'class'  	=>  'required',
		]);

		$data['input'] = $request->input();
		$data['selected_exam'] = Exam::Active()->CurrentSession()->where('id', $request->input('exam'))->first();

		if($data['selected_exam'] == null){
			return redirect('manage-result')->withInput()->with([
				'toastrmsg' => [
								'type'	=> 'error',
								'title'	=>  'Student Results',
								'msg'	=>  'Exam not found in selected session'
							]
			]);
		}

		$data['selected_class'] = Classe::findOrFail($request->input('class'));

		$data['transcripts'] = ExamRemark::where([
			'exam_id'	=>	$data['selected_exam']->id,
			'class_id'	=>	$data['selected_class']->id,
		])->with(['Student'	=>	function($qry){
			$qry->select('id', 'name', 'gr_no', 'father_name');
		}])->with(['StudentResult'	=>	function($qry){
			$qry->with('Subject')->with('SubjectResultAttribute');
		}])->get();

		return view('admin.make_transcript', $data);
	}

	public function SaveTranscript(Request $request){

		if($request->ajax()){

			$validator = Validator::make($request->all(), [
				'id' => 'required',
			]);

			if ($validator->fails()) {
				return  [
					'type'	=> 'error', 
					'title'	=>  'Student Results',
					'msg'	=>  'Something is wrong!'
				];
			}

			$ExamRemark = ExamRemark::findOrFail($request->input('id'));
			$ExamRemark->remarks = $request->input('remarks');
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
