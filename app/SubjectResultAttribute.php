<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubjectResultAttribute extends Model
{

	protected $fillable = [
		'subject_id', 'class_id',
		'exam_id', 'total_marks',
		'attributes'
	];

	protected $casts = [
		'attributes'	=>	'object',
		'attribute_marks'	=>	'object'
	];

	public function Subject(){
		return $this->belongsTo('App\Subject');
	}

	public function StudentResult(){
		return $this->hasMany('App\StudentResult');
	}

	public function Student(){
		return $this->belongsTo('app\Student');
	}

}
