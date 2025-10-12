<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Auth;

class Exam extends Model
{

	protected $casts	=	[
		'active'	=>	'boolean'
	];

	protected function getStartDateAttribute($start_date){
		return Carbon::createFromFormat('Y-m-d', $start_date)->format('d/m/Y');
	}

	protected function getEndDateAttribute($end_date){
		return Carbon::createFromFormat('Y-m-d', $end_date)->format('d/m/Y');
	}

	public function AcademicSession(){
		return $this->belongsTo('App\AcademicSession');
	}

	public function ExamRemarks(){
		return $this->hasMany('App\ExamRemark');
	}

	public function scopeCurrentSession($query){
		return $query->where('academic_session_id', Auth::user()->academic_session);
	}

	public function scopeActive($query){
		return $query->where('active', 1);
	}

}
