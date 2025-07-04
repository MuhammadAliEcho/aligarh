<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Auth;

class AcademicSession extends Model
{

	protected function getStartAttribute($start){
		return Carbon::createFromFormat('Y-m-d', $start)->format('d/m/Y');
	}

	protected function getEndAttribute($end){
		return Carbon::createFromFormat('Y-m-d', $end)->format('d/m/Y');
	}

	public function scopeUserAllowSession($query, $allow_session = null){
		return	$query->whereIn('id', $allow_session? $allow_session : Auth::user()->allow_session);
	}

	public function Exam(){
		return $this->hasMany('App\Exam');
	}

}
