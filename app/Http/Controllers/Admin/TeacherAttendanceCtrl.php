<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Model\Teacher;
use App\Model\TeacherAttendance;
use App\Model\Classe;
use App\Model\Section;
use DB;
use Carbon\Carbon;
use Auth;
use App\Http\Controllers\Controller;
use App\Jobs\SendAttendanceJob;

class TeacherAttendanceCtrl extends Controller
{
	public $notificationsSettingsName = 'teacher_attendance';
	public function Index(array $data = [], $job = ''){
		$data['root'] = $job;
		return view('admin.teachers_attendance', $data);
	}

	public function MakeAttendance(Request $request){
		$this->validate($request, [
	        'date'  	=>  'required',
    	]);
		$dbdate =	Carbon::createFromFormat('d/m/Y', $request->input('date'))->toDateString();
		$data['teachers'] = Teacher::withLeaveOn($dbdate)->get();

		foreach ($data['teachers'] as $k => $row) {
			$data['attendance'][$row->id] =	TeacherAttendance::select('id as attendance_id', 'status')->where(['teacher_id' => $row->id, 'date' => $dbdate])->first();
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
		$leave_ids = $request->input('teacher_leave');
		foreach($request->input('teacher_id') as $index => $teacher_id) {
			$TeacherAttendance = new TeacherAttendance;
			$att = $TeacherAttendance->where([
												'date' => $dbdate,
												'teacher_id' => $teacher_id,
												]);
			$attendance = $att->get();
			$forNotify = Teacher::select('name', 'email', 'phone')->find($teacher_id);

			if ($attendance->isEmpty()) {
				//SendAttendanceJob
				SendAttendanceJob::dispatch($this->notificationsSettingsName, $forNotify->name, $forNotify->email, $forNotify->phone, $forNotify->phone);

				$TeacherAttendance->teacher_id = $teacher_id;
				$TeacherAttendance->date = $dbdate;
				$TeacherAttendance->status = ($request->input('attendance'.$teacher_id) !== null)? 1 : 0;
				$TeacherAttendance->user_id	=	Auth::user()->id;
        		$TeacherAttendance->leave_id = $leave_ids[$index] ?? null;
				$TeacherAttendance->save();

			} else {
				$att->update(['status' => ($request->input('attendance'.$teacher_id) !== null)? 1 : 0]);
			}
	}
	return redirect('teacher-attendance')->with([
								'toastrmsg' => [
									'type' => 'success', 
									'title'  =>  __('modules.attendance_title'),
									'msg' =>  __('modules.attendance_job_success')
								]
							]); 
}	public function AttendanceReport(Request $request){
		$this->validate($request, [
			'date'  	=>  'required',
		]);

		$dbdate =	Carbon::createFromFormat('d/m/Y', '1/'.$request->input('date'));

		$data['teachers']	=	Teacher::all();
		foreach ($data['teachers'] as $k => $row) {
			$data['attendance'][$row->id] =	TeacherAttendance::select('id as attendance_id', 'date', 'status', 'leave_id')
												->where(['teacher_id' => $row->id])
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
