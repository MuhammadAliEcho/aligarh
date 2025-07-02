<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class InvoiceMaster extends Model
{

	protected $table = "invoice_master";

	protected $fillable = [
		'user_id', 'student_id', 'gr_no', 'payment_month',
		'total_amount', 'discount', 'paid_amount', 'payment_type',
		'chalan_no', 'date', 'date_of_payment', 'due_date', 'created_at', 'late_fee', 'net_amount'
	];

	public function getCreatedAtAttribute($date) {
		if (!$date) return null;
		try {
			return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('d-m-Y');
		} catch (\Exception $e) {
			return $date;
		}
	}

	public function getDueDateAttribute($date) {
		if (!$date) return null;
		try {
			return Carbon::createFromFormat('Y-m-d', $date)->format('d-m-Y');
		} catch (\Exception $e) {
			return $date;
		}
	}

	public function getPaymentMonthAttribute($payment_month) {
		if (!$payment_month) return null;
		try {
			return Carbon::createFromFormat('Y-m-d', $payment_month)->format('M-Y');
		} catch (\Exception $e) {
			return $payment_month;
		}
	}

	public function getUpdatedAtAttribute($date){
		if (!$date) return null;
		try {
			return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('Y-m-d');
		} catch (\Exception $e) {
			return $date;
		}

	}
	public function InvoiceDetail(){
		return $this->hasMany('App\InvoiceDetail', 'invoice_id', 'id');
	}

	public function InvoiceMonths(){
		return $this->hasMany('App\InvoiceMonth', 'invoice_id', 'id');
	}

	public function Student(){
		return $this->belongsTo('App\Student');
	}

}
