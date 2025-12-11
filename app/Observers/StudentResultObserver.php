<?php
namespace App\Observers;

use App\Model\StudentResult;
use Auth;

class StudentResultObserver {

	/**
	 * Listen to the User created event.
	 *
	 * @param  User  $user
	 * @return void
	 */
	public function creating(StudentResult $StudentResult)
	{
		$StudentResult->created_by  =   Auth::user()->id;
	}

	public function updating(StudentResult $StudentResult)
	{
		$StudentResult->updated_by  =   Auth::user()->id;
	}

	/**
	 * Listen to the User deleting event.
	 *
	 * @param  User  $user
	 * @return void
	 */
/*	public function deleting(StudentResult $StudentResult)
	{
		//
	}
*/
}