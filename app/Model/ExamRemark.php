<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ExamRemark extends Model
{

	protected $fillable = [
		'exam_id', 'class_id', 'student_id', 'remarks', 'rank'
	];

	public function StudentResult(){
		return	$this->hasMany('App\Model\StudentResult');
	}

	public function Student(){
		return	$this->belongsTo('App\Model\Student');
	}

	public function Classe(){
		return $this->belongsTo('App\Model\Classe', 'class_id');
	}

}
