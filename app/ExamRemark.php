<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExamRemark extends Model
{

	protected $fillable = [
		'exam_id', 'class_id', 'student_id', 'remarks'
	];

	public function StudentResult(){
		return	$this->hasMany('App\StudentResult');
	}

	public function Student(){
		return	$this->belongsTo('App\Student');
	}

	public function Classe(){
		return $this->belongsTo('App\Classe', 'class_id');
	}

}
