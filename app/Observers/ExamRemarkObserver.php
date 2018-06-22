<?php
namespace App\Observers;

use App\ExamRemark;
use Auth;

class ExamRemarkObserver {

	/**
	 * Listen to the User created event.
	 *
	 * @param  User  $user
	 * @return void
	 */
	public function creating(ExamRemark $ExamRemark)
	{
		$ExamRemark->created_by  =   Auth::user()->id;
	}

	public function updating(ExamRemark $ExamRemark)
	{
		$ExamRemark->updated_by  =   Auth::user()->id;
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