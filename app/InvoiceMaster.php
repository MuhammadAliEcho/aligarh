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
		return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('d-m-Y');
	}

	public function getDueDateAttribute($date) {
		return Carbon::createFromFormat('Y-m-d', $date)->format('d-m-Y');
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

	public function InvoiceMonths(){
		return $this->hasMany('App\InvoiceMonth', 'invoice_id', 'id');
	}

	public function Student(){
		return $this->belongsTo('App\Student');
	}

}
