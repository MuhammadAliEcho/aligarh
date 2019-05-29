<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\AdminContent;
use Illuminate\Support\Facades\Config;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

use App\Student;
use App\Employee;
use App\Teacher;
use App\Guardian;
use App\StudentAttendance;

class DashboardController extends Controller
{

//  protected $Routes;
	protected $data;

	public function __Construct($Routes){
//    $this->Routes = $Routes;
		$this->data['root'] = $Routes;
	}

	public function GetDashboard(){

//		dd(Student::active()->count());
		$this->data['no_of_student'] = Student::active()->count();
		$this->data['no_of_teachers'] = Teacher::count();
		$this->data['no_of_employee'] = Employee::count();
		$this->data['student_attendance'] = StudentAttendance::where('date', Carbon::now()->toDateString())->where('status', 1)->count();
		return view('admin.dashboard', $this->data);
	}

	public function errors(){
		return view('errors.404');
	}

/*  public function navigation(){

	}
*/

}
