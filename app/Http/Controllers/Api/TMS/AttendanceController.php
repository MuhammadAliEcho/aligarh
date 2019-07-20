<?php

namespace App\Http\Controllers\Api\TMS;

use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

use App\Student;
use App\StudentAttendance;
use App\Teacher;
use App\TeacherAttendance;
use App\Employee;
use App\EmployeeAttendance;

class AttendanceController extends Controller
{

    Public function Attendance(Request $request){

        Validator::make($request->all(), [
            'type' => ['required', 'regex:(student|teacher|employee)'],
//            'id' => 'required|max:255',
        ])->validate();

        Validator::make($request->all(), [
		    'id' => 'required|max:255|exists:'.$request->input('type').'s,id',
        ])->validate();

        switch ($request->input('type')) {
            case 'student':
				return	$this->StudentAttendance($request->input('id'));
                break;
				
			case 'teacher':
				return	$this->TeacherAttendance($request->input('id'));
                break;
				
			case 'employee':
				return	$this->EmployeeAttendance($request->input('id'));
                break;
        }

	}
	
	Public function CacheData(Request $request){

        Validator::make($request->all(), [
            'data' => 'required',
        ])->validate();

		foreach ($request->input('data') as $key => $value) {
			$value = json_decode($value);
			switch ($value->type) {
				case 'student':
					$this->StudentAttendance($value->id);
					break;
					
				case 'teacher':
					$this->TeacherAttendance($value->id);
					break;
					
				case 'employee':
					$this->EmployeeAttendance($value->id);
					break;
			}
		}

        return response()->json(['msg' => 'Sync Successfull']);

	}

    protected function StudentAttendance($id){
		$student = Student::where('id', $id)->Active()->first();
		if($student){
			StudentAttendance::updateOrCreate(
				[
					'date'		 => Carbon::now()->toDateString(),
					'student_id' => $student->id,
				],
				[
					'status'	=>	1,
					]
				);
				
				if($student->image_dir){
					$image = Storage::get($student->image_dir);
					$type = pathinfo($student->image_dir, PATHINFO_EXTENSION);
				}
			return [
				'data'	=>	$student,
				'time_stamp'	=>	Carbon::now()->toDateTimeString(),
				'image_base64' => $student->image_dir? 'data:image/' . $type . ';base64,' . base64_encode($image) : '',
			];
		} else {
            return response()->json(['msg' => ['Inactive Student/Data Not Found!']], 422);
		}
	}

    protected function TeacherAttendance($id){
		$teacher = Teacher::where('id', $id)->first();
		if($teacher){
			TeacherAttendance::updateOrCreate(
				[
					'date'		 => Carbon::now()->toDateString(),
					'teacher_id' => $teacher->id,
				],
				[
					'status'	=>	1,
					]
				);
				if($teacher->image_dir){
					$image = Storage::get($teacher->image_dir);
					$type = pathinfo($teacher->image_dir, PATHINFO_EXTENSION);
				}
			return [
					'data'	=>	$teacher,
					'time_stamp'	=>	Carbon::now()->toDateTimeString(),
					'image_base64' => $teacher->image_dir? 'data:image/' . $type . ';base64,' . base64_encode($image) : '',
				];
		} else {
            return response()->json(['msg' => ['Inactive Teacher/Data Not Found!']], 422);
		}
    }

    protected function EmployeeAttendance($id){
		$employee = Employee::where('id', $id)->first();
		if($employee){
			EmployeeAttendance::updateOrCreate(
				[
					'date'		 => Carbon::now()->toDateString(),
					'employee_id' => $employee->id,
				],
				[
					'status'	=>	1,
				]
			);
			if($employee->image_dir){
				$image = Storage::get($employee->image_dir);
				$type = pathinfo($employee->image_dir, PATHINFO_EXTENSION);
			}
			return [
					'data'	=>	$employee,
					'time_stamp'	=>	Carbon::now()->toDateTimeString(),
					'image_base64' => $employee->image_dir? 'data:image/' . $type . ';base64,' . base64_encode($image) : '',
				];
		} else {
            return response()->json(['msg' => ['Inactive Employee/Data Not Found!']], 422);
		}
    }

}
