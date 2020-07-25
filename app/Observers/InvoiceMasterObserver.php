<?php
namespace App\Observers;

use App\InvoiceMaster;
use Auth;

class InvoiceMasterObserver {

	/**
	 * Listen to the User created event.
	 *
	 * @param  User  $user
	 * @return void
	 */
	public function creating(InvoiceMaster $InvoiceMaster)
	{
		$InvoiceMaster->created_by  =   Auth::user()->id;
	}

	public function updating(InvoiceMaster $InvoiceMaster)
	{
		$InvoiceMaster->updated_by  =   Auth::user()->id;
	}

	/**
	 * Listen to the User deleting event.
	 *
	 * @param  User  $user
	 * @return void
	 */
/*	public function deleting(InvoiceMaster $InvoiceMaster)
	{
		//
	}*/
}