<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class InvoiceMaster extends Model
{


	public function getCreatedAtAttribute($date) {
		return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('d-m-Y');
	}

	public function getPaymentMonthAttribute($payment_month) {
		return Carbon::createFromFormat('Y-m-d', $payment_month)->format('M-Y');
	}

	public function getUpdatedAtAttribute($date){
		return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('Y-m-d');
	}

	public function InvoiceDetail(){
		return $this->hasMany('App\InvoiceDetail', 'invoice_id', 'id');
	}

	public function Student(){
		return $this->belongsTo('App\Student');
	}

protected $table = "invoice_master";
}
