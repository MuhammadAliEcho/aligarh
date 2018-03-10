<?php

namespace App\Http\Controllers;

//use Illuminate\Http\Request;

use Request;
use App\Http\Requests;
use App\Teacher;
use App\TeacherAttendance;
use App\Classe;
use App\Section;
use DB;
use Carbon\Carbon;
use Auth;

class TeacherAttendanceCtrl extends Controller
{

	//  protected $Routes;
	protected $data, $Attendence, $Request;

	public function __Construct($Routes, $Request){
		$this->data['root'] = $Routes;
		$this->Request = $Request;
	}

	public function Index(){
		return view('teachers_attendance', $this->data);
	}

	public function MakeAttendance(){
		$this->validate($this->Request, [
	        'date'  	=>  'required',
    	]);
		$dbdate =	Carbon::createFromFormat('d/m/Y', $this->Request->input('date'))->toDateString();
		$this->data['teachers']	=	Teacher::all();
		foreach ($this->data['teachers'] as $k => $row) {
			$this->data['attendance'][$row->id] =	TeacherAttendance::select('id as attendance_id', 'status')->where(['teacher_id' => $row->id, 'date' => $dbdate])->first();
		}
		$this->data['input'] = $this->Request->input();
		return $this->Index();
	}

	public function UpdateAttendance(){
		$this->validate($this->Request, [
			'date'  	=>  'required',
		]);
		$dbdate =	Carbon::createFromFormat('d/m/Y', $this->Request->input('date'))->toDateString();
		foreach($this->Request->input('teacher_id') as $teacher_id) {
			$TeacherAttendance = new TeacherAttendance;
			$att = $TeacherAttendance->where([
												'date' => $dbdate,
												'teacher_id' => $teacher_id,
												]);
			$attendance = $att->get();
			if ($attendance->isEmpty()) {

				$TeacherAttendance->teacher_id = $teacher_id;
				$TeacherAttendance->date = $dbdate;
				$TeacherAttendance->status = ($this->Request->input('attendance'.$teacher_id) !== null)? 1 : 0;
				$TeacherAttendance->user_id	=	Auth::user()->id;
				$TeacherAttendance->save();

			} else {
				$att->update(['status' => ($this->Request->input('attendance'.$teacher_id) !== null)? 1 : 0]);
			}
		}
		return redirect('teacher-attendance')->with([
									'toastrmsg' => [
										'type' => 'success', 
										'title'  =>  'Teacher Attendance',
										'msg' =>  'Attendance Job Successfull'
									]
								]); 
	}

	public function AttendanceReport(){
		$this->validate($this->Request, [
			'date'  	=>  'required',
		]);

		$dbdate =	Carbon::createFromFormat('d/m/Y', '1/'.$this->Request->input('date'));

		$this->data['teachers']	=	Teacher::all();
		foreach ($this->data['teachers'] as $k => $row) {
			$this->data['attendance'][$row->id] =	TeacherAttendance::select('id as attendance_id', 'date', 'status')
												->where(['teacher_id' => $row->id])
												->where('date', '>=', $dbdate->startOfMonth()->toDateString())
												->where('date', '<=', $dbdate->endOfMonth()->toDateString())
												->orderby('date')
												->get();
		}

		$this->data['input'] = $this->Request->input();
		$this->data['dbdate']['noofdays'] = $dbdate->endOfMonth()->day;
//		return response($this->data['attendence']);
		return $this->Index();
	}

}
