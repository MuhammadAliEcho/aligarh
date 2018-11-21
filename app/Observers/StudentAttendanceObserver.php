<?php
namespace App\Observers;

use App\StudentAttendance;
use Auth;

class StudentAttendanceObserver {

	/**
	 * Listen to the User created event.
	 *
	 * @param  User  $user
	 * @return void
	 */
	public function creating(StudentAttendance $StudentAttendance)
	{
		$StudentAttendance->created_by  =   Auth::user()->id;
	}

	public function updating(StudentAttendance $StudentAttendance)
	{
		$StudentAttendance->updated_by  =   Auth::user()->id;
	}

	/**
	 * Listen to the User deleting event.
	 *
	 * @param  User  $user
	 * @return void
	 */
/*	public function deleting(ExamRemark $ExamRemark)
	{
		//
	}*/
}