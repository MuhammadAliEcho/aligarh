<?php

namespace App\Http\Controllers\Admin;

//use Yajra\DataTables\Facades\DataTables;
//use Illuminate\Http\Request as InputRequest;
use Request;
use App\Http\Requests;
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

	//  protected $Routes;
	protected $data, $Attendance, $Request, $DateRange, $AcademicSession;

	public function __Construct($Routes, $Request){
		Carbon::setWeekendDays([
			Carbon::SUNDAY,
			Carbon::SATURDAY,
		]);
		$this->data['root'] = $Routes;
		$this->Request = $Request;
	}

	public function Index(){
		$this->data['classes'] = Classe::select('id', 'name')->get();
		foreach ($this->data['classes'] as $key => $class) {
			$this->data['sections']['class_'.$class->id] = Section::select('name', 'id')->where(['class_id' => $class->id])->get();
		}
		return view('admin.students_attendance', $this->data);
	}

	public function MakeAttendance(){
		$this->validate($this->Request, [
	        'class'  	=>  'required|numeric',
//	        'section'  	=>  'required|numeric',
	        'date'  	=>  'required',
    	]);
		$dbdate =	Carbon::createFromFormat('d/m/Y', $this->Request->input('date'));
		$this->DateRange = $dbdate->toDateString();
		if($dbdate->isWeekend()){
			return redirect('student-attendance')->withInput()->with([
				'toastrmsg' => [
					'type' => 'error',
					'title'  =>  'Student Attendance',
					'msg' =>  'Selected Date is weekend'
					]
			]);
		}

		$this->AcademicSession = Auth::user()->AcademicSession()->first();

		if($this->AcademicSession->getOriginal('start') > $this->DateRange || $this->AcademicSession->getOriginal('end') < $this->DateRange){
			return redirect('student-attendance')->withInput()->with([
				'toastrmsg' => [
					'type' => 'error',
					'title'  =>  'Student Attendance',
					'msg' =>  'Selected Date is Invalid'
					]
			]);
		}

		$this->data['students'] = Student::join('academic_session_history', 'students.id', '=', 'academic_session_history.student_id')
									->select('students.id', 'students.name', 'students.gr_no', 'academic_session_history.class_id AS session_history_class_id', 'students.class_id AS current_class_id')
									->where([
										'academic_session_history.class_id' => $this->Request->input('class'),
										'academic_session_history.academic_session_id' => Auth::user()->academic_session
										])
									->where('students.date_of_enrolled', '<=', $this->DateRange);

		if ($this->Request->has('section')) {
			$this->data['students']->where(['students.section_id' => $this->Request->input('section')]);
			$this->data['section'] = Section::find($this->Request->input('section'));
		}

		$this->data['students']	=	$this->data['students']->active()->orderBy('students.name')->with(['StudentAttendanceByDate'	=>	function($qry){
			$qry->select('id', 'student_id', 'date', 'status')
				->where(['date'	=>	$this->DateRange]);
		}])->get();
//		dd($this->data['students']);

		$this->data['input'] = $this->Request->input();
		$this->data['selected_class'] = Classe::find($this->Request->input('class'));
		$this->data['section_nick'] = empty($this->data['section'])? 'ALL' : $this->data['section']->nick_name;
		return $this->Index();

	}

	public function UpdateAttendance(){
		$this->validate($this->Request, [
			'date'  	=>  'required',
		]);
		$dbdate =	Carbon::createFromFormat('d/m/Y', $this->Request->input('date'))->toDateString();
		if($this->Request->has('student_id')){
			foreach($this->Request->input('student_id') as $student_id) {
				StudentAttendance::updateOrCreate(
					[
						'date'		 => $dbdate,
						'student_id' => $student_id,
					],
					[
						'status'	=>	($this->Request->input('attendance'.$student_id) !== null)? 1 : 0,
					]
				);
			}
		}
		if($this->Request->has('delete')){
			StudentAttendance::where('date', $dbdate)
								->whereIn('student_id', $this->Request->input('delete'))
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

	public function AttendanceReport(){
		$this->validate($this->Request, [
			'class'  	=>  'required',
			'date'  	=>  'required',
		]);

		$dbdate = Carbon::createFromFormat('d/m/Y', '1/'.$this->Request->input('date'));
		$this->DateRange	=	[
			'start'	=>	$dbdate->startOfMonth()->toDateString(),
			'end'	=>	$dbdate->endOfMonth()->toDateString()
		];

		$this->AcademicSession = Auth::user()->AcademicSession()->first();

		if($this->AcademicSession->getOriginal('start') > $this->DateRange['start'] || $this->AcademicSession->getOriginal('end') < $this->DateRange['end']){
			return redirect('student-attendance')->withInput()->with([
				'toastrmsg' => [
					'type' => 'error',
					'title'  =>  'Student Attendance',
					'msg' =>  'Selected Date is Invalid'
					]
			]);
		}

		$this->data['students'] = Student::join('academic_session_history', 'students.id', '=', 'academic_session_history.student_id')
									->select('students.id', 'students.name', 'students.gr_no', 'academic_session_history.class_id AS session_history_class_id', 'students.class_id AS current_class_id')
									->where([
										'academic_session_history.class_id' => $this->Request->input('class'),
										'academic_session_history.academic_session_id' => Auth::user()->academic_session
										])
									->where('students.date_of_enrolled', '<=', $this->DateRange['end']);

		if ($this->Request->has('section')) {
			$this->data['students']->where(['students.section_id' => $this->Request->input('section')]);
		}

		$this->data['students']	=	$this->data['students']->orderBy('students.name')
									->where(function($qry){
										$qry->Active()
											->orWhere('students.date_of_leaving', '>=', $this->DateRange['start']);
									})
									->with(['StudentAttendance'	=>	function($qry){
			$qry->select('id', 'student_id', 'date', 'status')
				->whereBetween('date', $this->DateRange)
				->orderby('date');
		}])->get();

//		$this->data['students']	=	$this->data['students']->CurrentSession()->get();
		$this->data['input'] = $this->Request->input();
		$this->data['selected_class'] = Classe::find($this->Request->input('class'));
		$this->data['section'] = Section::find($this->Request->input('section'));
		$this->data['section_nick'] = empty($this->data['section'])? '' : $this->data['section']->nick_name;

		$loopdate =	CarbonPeriod::create($this->DateRange['start'], $this->DateRange['end']);
		$this->data['noofdays'] = $loopdate->count();

		foreach ($loopdate as $d) {
			if($d->isWeekend()){
				$this->data['weekends'][] = $d->day;
			}
		}

		$this->data['noofweekends'] = COUNT($this->data['weekends']);
		$this->data['input']['date'] = $dbdate->format('M-Y');

		return view('admin.printable.students_attendance', $this->data);
	}

}
