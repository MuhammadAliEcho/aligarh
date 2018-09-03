<?php

namespace App\Http\Controllers\Admin;

//use Yajra\Datatables\Facades\Datatables;
//use Illuminate\Http\Request as InputRequest;
use Request;
use App\Http\Requests;
use App\Student;
use App\StudentAttendence;
use App\Classe;
use App\Section;
use DB;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Auth;
use App\Http\Controllers\Controller;

class StudentAttendenceCtrl extends Controller
{

	//  protected $Routes;
	protected $data, $Attendence, $Request;

	public function __Construct($Routes, $Request){
		$this->data['root'] = $Routes;
		$this->Request = $Request;
	}

	public function Index(){
		$this->data['classes'] = Classe::select('id', 'name')->get();
		foreach ($this->data['classes'] as $key => $class) {
			$this->data['sections']['class_'.$class->id] = Section::select('name', 'id')->where(['class_id' => $class->id])->get();
		}
		return view('admin.students_attendence', $this->data);
	}

	public function MakeAttendence(){
		$this->validate($this->Request, [
	        'class'  	=>  'required|numeric',
//	        'section'  	=>  'required|numeric',
	        'date'  	=>  'required',
    	]);
		$dbdate =	Carbon::createFromFormat('d/m/Y', $this->Request->input('date'))->toDateString();

/*		$qry = DB::table('students')
					->select('students.id', 'students.name', 'students.gr_no')
					->where(['students.class_id' => $this->Request->input('class')]);
*/
		$this->data['students'] = Student::select('students.id', 'students.name', 'students.gr_no')
									->where(['students.class_id' => $this->Request->input('class')]);

		if ($this->Request->has('section')) {
			$this->data['students']->where(['students.section_id' => $this->Request->input('section')]);
			$this->data['section'] = Section::find($this->Request->input('section'));
		}

		$this->data['students']	=	$this->data['students']->active()->orderBy('name')->get();
		foreach ($this->data['students'] as $k => $row) {
			$this->data['attendence'][$row->id] =	StudentAttendence::select('id as attendence_id', 'status')->where(['student_id' => $row->id, 'date' => $dbdate])->first();
		}
		$this->data['input'] = $this->Request->input();
		$this->data['selected_class'] = Classe::find($this->Request->input('class'));
		$this->data['section_nick'] = empty($this->data['section'])? 'ALL' : $this->data['section']->nick_name;
		// echo response($this->data['students']);
		// echo response($this->data['attendence']);
		//	Carbon::createFromFormat('d/m/Y', '')->toDateString();
		return $this->Index();

	}

	public function UpdateAttendence(){
		$this->validate($this->Request, [
			'date'  	=>  'required',
		]);
		$dbdate =	Carbon::createFromFormat('d/m/Y', $this->Request->input('date'))->toDateString();
		foreach($this->Request->input('student_id') as $student_id) {
			StudentAttendence::updateOrCreate(
				[
					'date'		 => $dbdate,
					'student_id' => $student_id,
				],
				[
					'status'	=>	($this->Request->input('attendence'.$student_id) !== null)? 1 : 0,
					'user_id'	=>	Auth::user()->id
				]
			);
		}
		return redirect('student-attendance')->with([
									'toastrmsg' => [
										'type' => 'success', 
										'title'  =>  'Student Attendence',
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

		$qry = DB::table('students')
					->select('students.id', 'students.name', 'students.gr_no')
					->where(['students.class_id' => $this->Request->input('class')]);

//		$this->data['students'] = Student::select('id', 'name', 'gr_no')->where(['class_id' => $this->Request->input('class')]);

/*		if ($this->Request->has('section')) {
			$this->data['students']->where(['students.section_id' => $this->Request->input('section')]);
		}
*/
		if ($this->Request->has('section')) {
			$qry->where(['students.section_id' => $this->Request->input('section')]);
		}

		$this->data['students']	=	$qry->orderBy('name')->get();
//		$this->data['students']	=	$this->data['students']->CurrentSession()->get();
		$this->data['attendence'] = [];
		foreach ($this->data['students'] as $k => $row) {
			$this->data['attendence'][$row->id] =	StudentAttendence::select('id as attendence_id', 'date', 'status')
												->where(['student_id' => $row->id])
												->where('date', '>=', $dbdate->startOfMonth()->toDateString())
												->where('date', '<=', $dbdate->endOfMonth()->toDateString())
												->orderby('date')
												->get();
		}
		$this->data['input'] = $this->Request->input();
		$this->data['selected_class'] = Classe::find($this->Request->input('class'));
		$this->data['section'] = Section::find($this->Request->input('section'));
		$this->data['section_nick'] = empty($this->data['section'])? '' : $this->data['section']->nick_name;

		$loopdate =	CarbonPeriod::create($dbdate->startOfMonth()->toDateString(), $dbdate->endOfMonth()->toDateString());
		$this->data['noofdays'] = $loopdate->count();
		Carbon::setWeekendDays([
			Carbon::SUNDAY,
			Carbon::SATURDAY,
		]);

		foreach ($loopdate as $d) {
			if($d->isWeekend()){
				$this->data['weekends'][] = $d->day;
			}
		}

		$this->data['noofweekends'] = COUNT($this->data['weekends']);
		$this->data['input']['date'] = $dbdate->format('M-Y');

		return view('admin.printable.students_attendence', $this->data);
	}

}
