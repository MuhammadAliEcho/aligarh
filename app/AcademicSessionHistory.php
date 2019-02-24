<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class AcademicSessionHistory extends Model
{
	const UPDATED_AT = null;
	protected $table = 'academic_session_history';

	protected $fillable = [
		'student_id', 'class_id', 'academic_session_id', 'created_by'
	];

	public function Classe(){
		return $this->belongsTo('App\Classe', 'class_id');
	}

	public function scopeCurrentSession($query){
		return $query->where('academic_session_id', Auth::user()->academic_session);
	}

}
