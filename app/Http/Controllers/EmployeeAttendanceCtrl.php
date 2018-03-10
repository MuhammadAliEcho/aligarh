<?php

namespace App\Http\Controllers;

//use Illuminate\Http\Request;

use Request;
use App\Http\Requests;

use App\Employee;
use App\EmployeeAttendance;
use DB;
use Carbon\Carbon;
use Auth;

class EmployeeAttendanceCtrl extends Controller
{

	//  protected $Routes;
	protected $data, $Attendence, $Request;

	public function __Construct($Routes, $Request){
		$this->data['root'] = $Routes;
		$this->Request = $Request;
	}

	public function Index(){
		return view('employees_attendance', $this->data);
	}

	public function MakeAttendance(){
		$this->validate($this->Request, [
	        'date'  	=>  'required',
    	]);
		$dbdate =	Carbon::createFromFormat('d/m/Y', $this->Request->input('date'))->toDateString();
		$this->data['employees']	=	Employee::all();
		foreach ($this->data['employees'] as $k => $row) {
			$this->data['attendance'][$row->id] =	EmployeeAttendance::select('id as attendance_id', 'status')->where(['employee_id' => $row->id, 'date' => $dbdate])->first();
		}
		$this->data['input'] = $this->Request->input();
		return $this->Index();
	}

	public function UpdateAttendance(){
		$this->validate($this->Request, [
			'date'  	=>  'required',
		]);
		$dbdate =	Carbon::createFromFormat('d/m/Y', $this->Request->input('date'))->toDateString();
		foreach($this->Request->input('employee_id') as $employee_id) {
			$EmployeeAttendance = new EmployeeAttendance;
			$att = $EmployeeAttendance->where([
												'date' => $dbdate,
												'employee_id' => $employee_id,
												]);
			$attendance = $att->get();
			if ($attendance->isEmpty()) {

				$EmployeeAttendance->employee_id = $employee_id;
				$EmployeeAttendance->date = $dbdate;
				$EmployeeAttendance->status = ($this->Request->input('attendance'.$employee_id) !== null)? 1 : 0;
				$EmployeeAttendance->user_id	=	Auth::user()->id;
				$EmployeeAttendance->save();

			} else {
				$att->update(['status' => ($this->Request->input('attendance'.$employee_id) !== null)? 1 : 0]);
			}
		}
		return redirect('employee-attendance')->with([
									'toastrmsg' => [
										'type' => 'success', 
										'title'  =>  'Employee Attendance',
										'msg' =>  'Attendance Job Successfull'
									]
								]); 
	}

	public function AttendanceReport(){
		$this->validate($this->Request, [
			'date'  	=>  'required',
		]);

		$dbdate =	Carbon::createFromFormat('d/m/Y', '1/'.$this->Request->input('date'));

		$this->data['employees']	=	Employee::all();
		foreach ($this->data['employees'] as $k => $row) {
			$this->data['attendance'][$row->id] =	EmployeeAttendance::select('id as attendance_id', 'date', 'status')
												->where(['employee_id' => $row->id])
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
