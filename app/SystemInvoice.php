<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class SystemInvoice extends Model
{


	public function getCreatedAtAttribute($date) {
		return Carbon::parse($date)->format('d-m-Y');
	}

	public function getBillingMonthAttribute($payment_month) {
		return Carbon::createFromFormat('Y-m-d', $payment_month)->format('M-Y');
	}

	public function getDateOfPaymentAttribute($date) {
		if($date){
			return Carbon::createFromFormat('Y-m-d', $date)->format('d-m-Y');
		}
	}

	public function getUpdatedAtAttribute($date){
		return Carbon::parse($date)->format('Y-m-d');
	}


}
