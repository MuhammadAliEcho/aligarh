<?php

namespace App\Http\Controllers\Admin;

//use Yajra\DataTables\Facades\DataTables;
//use Illuminate\Http\Request as InputRequest;
use Illuminate\Http\Request;
use App\Student;
use App\StudentAttendance;
use App\Classe;
use App\Section;
use DB;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Auth;
use App\Http\Controllers\Controller;
use App\AcademicSession;

class StudentAttendanceCtrl extends Controller
{

	public function Index($data = null, $root = 0){
		$data['classes'] = Classe::select('id', 'name')->get();
		foreach ($data['classes'] as $key => $class) {
			$data['sections']['class_'.$class->id] = Section::select('name', 'id')->where(['class_id' => $class->id])->get();
		}

		$data['root']= $root;
		return view('admin.students_attendance', $data);
	}

	public function MakeAttendance(Request $request){
		$this->validate($request, [
	        'class'  	=>  'required|numeric',
			//'section'  	=>  'required|numeric',
	        'date'  	=>  'required',
    	]);
		$dbdate =	Carbon::createFromFormat('d/m/Y', $request->input('date'));
		$DateRange = $dbdate->toDateString();
		if($dbdate->isWeekend()){
			return redirect('student-attendance')->withInput()->with([
				'toastrmsg' => [
					'type' => 'error',
					'title'  =>  'Student Attendance',
					'msg' =>  'Selected Date is weekend'
					]
			]);
		}

		$AcademicSession = Auth::user()->AcademicSession()->first();

		if($AcademicSession->getOriginal('start') > $DateRange || $AcademicSession->getOriginal('end') < $DateRange){
			return redirect('student-attendance')->withInput()->with([
				'toastrmsg' => [
					'type' => 'error',
					'title'  =>  'Student Attendance',
					'msg' =>  'Selected Date is Invalid'
					]
			]);
		}

		$data['students'] = Student::join('academic_session_history', 'students.id', '=', 'academic_session_history.student_id')
									->select('students.id', 'students.name', 'students.gr_no', 'academic_session_history.class_id AS session_history_class_id', 'students.class_id AS current_class_id')
									->where([
										'academic_session_history.class_id' => $request->input('class'),
										'academic_session_history.academic_session_id' => Auth::user()->academic_session
										])
									->where('students.date_of_enrolled', '<=', $DateRange);

		if ($request->has('section')) {
			$data['students']->where(['students.section_id' => $request->input('section')]);
			$data['section'] = Section::find($request->input('section'));
		}

		$data['students']	=	$data['students']->active()->orderBy('students.name')->with(['StudentAttendanceByDate'	=>	function($qry) use ($DateRange){
			$qry->select('id', 'student_id', 'date', 'status')
				->where(['date'	=>	$DateRange]);
		}])->get();

		$data['input'] = $request->input();
		$data['selected_class'] = Classe::find($request->input('class'));
		$data['section_nick'] = empty($data['section'])? 'ALL' : $data['section']->nick_name;
		return $this->Index($data, 1);

	}

	public function UpdateAttendance(Request $request){
		$this->validate($request, [
			'date'  	=>  'required',
		]);
		$dbdate =	Carbon::createFromFormat('d/m/Y', $request->input('date'))->toDateString();
		if($request->has('student_id')){
			foreach($request->input('student_id') as $student_id) {
				StudentAttendance::updateOrCreate(
					[
						'date'		 => $dbdate,
						'student_id' => $student_id,
					],
					[
						'status'	=>	($request->input('attendance'.$student_id) !== null)? 1 : 0,
					]
				);
			}
		}
		if($request->has('delete')){
			StudentAttendance::where('date', $dbdate)
								->whereIn('student_id', $request->input('delete'))
								->delete();
		}
		return redirect('student-attendance')->with([
									'toastrmsg' => [
										'type' => 'success', 
										'title'  =>  'Student Attendance',
										'msg' =>  'Attendance Job Successfull'
									]
								]); 
	}

	public function AttendanceReport(Request $request){
		$this->validate($request, [
			'class'  	=>  'required',
			'date'  	=>  'required',
		]);

		$dbdate = Carbon::createFromFormat('d/m/Y', '1/'.$request->input('date'));
		$DateRange	=	[
			'start'	=>	$dbdate->startOfMonth()->toDateString(),
			'end'	=>	$dbdate->endOfMonth()->toDateString()
		];

		$AcademicSession = Auth::user()->AcademicSession()->first();

		if($AcademicSession->getOriginal('start') > $DateRange['start'] || $AcademicSession->getOriginal('end') < $DateRange['end']){
			return redirect('student-attendance')->withInput()->with([
				'toastrmsg' => [
					'type' => 'error',
					'title'  =>  'Student Attendance',
					'msg' =>  'Selected Date is Invalid'
					]
			]);
		}

		$data['students'] = Student::join('academic_session_history', 'students.id', '=', 'academic_session_history.student_id')
									->select('students.id', 'students.name', 'students.gr_no', 'academic_session_history.class_id AS session_history_class_id', 'students.class_id AS current_class_id')
									->where([
										'academic_session_history.class_id' => $request->input('class'),
										'academic_session_history.academic_session_id' => Auth::user()->academic_session
										])
									->where('students.date_of_enrolled', '<=', $DateRange['end']);

		if ($request->has('section')) {
			$data['students']->where(['students.section_id' => $request->input('section')]);
		}

		$data['students']	=	$data['students']->orderBy('students.name')
									->where(function($qry) use ($DateRange) {
										$qry->Active()
											->orWhere('students.date_of_leaving', '>=', $DateRange['start']);
									})
									->with(['StudentAttendance'	=>	function($qry) use ($DateRange) {
			$qry->select('id', 'student_id', 'date', 'status')
				->whereBetween('date', $DateRange)
				->orderby('date');
		}])->get();

		//$data['students']	=	$data['students']->CurrentSession()->get();
		$data['input'] = $request->input();
		$data['selected_class'] = Classe::find($request->input('class'));
		$data['section'] = Section::find($request->input('section'));
		$data['section_nick'] = empty($data['section'])? '' : $data['section']->nick_name;

		$loopdate =	CarbonPeriod::create($DateRange['start'], $DateRange['end']);
		$data['noofdays'] = $loopdate->count();

		foreach ($loopdate as $d) {
			if($d->isWeekend()){
				$data['weekends'][] = $d->day;
			}
		}

		$data['noofweekends'] = COUNT($data['weekends']);
		$data['input']['date'] = $dbdate->format('M-Y');

		return view('admin.printable.students_attendance', $data);
	}

}
