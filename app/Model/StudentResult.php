<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class StudentResult extends Model
{

	protected $casts	=	[
		'obtain_marks'	=>	'object'
	];

	protected $fillable = [
		'student_id', 'subject_id', 'exam_id', 'exam_remark_id',
		'subject_result_attribute_id',  'obtain_marks', 'total_obtain_marks'
	];

	public function ExamRemark(){
		return	$this->belongsTo('App\Model\ExamRemark');
	}

	public function Student(){
		return $this->belongsTo('App\Model\Student');
	}

	public function Subject(){
		return $this->belongsTo('App\Model\Subject');
	}

	public function SubjectResultAttribute(){
		return $this->belongsTo('App\Model\SubjectResultAttribute');
	}

}
