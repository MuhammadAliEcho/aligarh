<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class InvoiceMonth extends Model
{
	public $timestamps = false;

	protected $fillable = [
		'invoice_id', 'student_id', 'month',
    ];

	public function Student(){
		return $this->belongsTo('App\Model\Student');
    }
    
	public function Invoice(){
		return $this->belongsTo('App\Model\InvoiceMaster', 'invoice_id');
	}

	public function getMonthAttribute($month) {
		return Carbon::createFromFormat('Y-m-d', $month)->format('M-Y');
	}
}
