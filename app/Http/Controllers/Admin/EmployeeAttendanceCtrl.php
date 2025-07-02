<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Employee;
use App\EmployeeAttendance;
use DB;
use Carbon\Carbon;
use Auth;
use App\Http\Controllers\Controller;

class EmployeeAttendanceCtrl extends Controller
{
	public function Index(array $data = [], $job = ''){
		$data['root'] = $job;
		return view('admin.employees_attendance', $data);
	}

	public function MakeAttendance(Request $request){
		$this->validate($request, [
	        'date'  	=>  'required',
    	]);
		$dbdate =	Carbon::createFromFormat('d/m/Y', $request->input('date'))->toDateString();
		$data['employees']	=	Employee::all();
		foreach ($data['employees'] as $k => $row) {
			$data['attendance'][$row->id] =	EmployeeAttendance::select('id as attendance_id', 'status')->where(['employee_id' => $row->id, 'date' => $dbdate])->first();
		}
		$data['input'] = $request->input();
		$job = 'make';
		return $this->Index($data, $job);
	}

	public function UpdateAttendance(Request $request){
		$this->validate($request, [
			'date'  	=>  'required',
		]);
		$dbdate =	Carbon::createFromFormat('d/m/Y', $request->input('date'))->toDateString();
		foreach($request->input('employee_id') as $employee_id) {
			$EmployeeAttendance = new EmployeeAttendance;
			$att = $EmployeeAttendance->where([
												'date' => $dbdate,
												'employee_id' => $employee_id,
												]);
			$attendance = $att->get();
			if ($attendance->isEmpty()) {

				$EmployeeAttendance->employee_id = $employee_id;
				$EmployeeAttendance->date = $dbdate;
				$EmployeeAttendance->status = ($request->input('attendance'.$employee_id) !== null)? 1 : 0;
				$EmployeeAttendance->user_id	=	Auth::user()->id;
				$EmployeeAttendance->save();

			} else {
				$att->update(['status' => ($request->input('attendance'.$employee_id) !== null)? 1 : 0]);
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

	public function AttendanceReport(Request $request){
		$this->validate($request, [
			'date'  	=>  'required',
		]);

		$dbdate =	Carbon::createFromFormat('d/m/Y', '1/'.$request->input('date'));

		$data['employees']	=	Employee::all();
		foreach ($data['employees'] as $k => $row) {
			$data['attendance'][$row->id] =	EmployeeAttendance::select('id as attendance_id', 'date', 'status')
												->where(['employee_id' => $row->id])
												->where('date', '>=', $dbdate->startOfMonth()->toDateString())
												->where('date', '<=', $dbdate->endOfMonth()->toDateString())
												->orderby('date')
												->get();
		}

		$data['input'] = $request->input();
		$data['dbdate']['noofdays'] = $dbdate->endOfMonth()->day;
//		return response($data['attendance']);
		$job = 'report';
		return $this->Index($data, $job);
	}

}
