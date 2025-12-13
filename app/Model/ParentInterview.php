<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ParentInterview extends Model
{

	protected $fillable = [
		'student_id', 'father_qualification', 'mother_qualification',
		'father_occupation', 'mother_occupation', 'monthly_income',
		'other_job_father', 'other_job_mother', 'family_structure',
		'parents_living', 'no_of_children', 'questions',
		'questions_montessori', 'remarks',
	];

	protected $casts = [
		'questions'					=>	'object',
		'questions_montessori'		=>	'object',
		'no_of_children'			=>	'object',
	];

	public function Student(){
		return $this->belongsTo('App\Model\Student');
	}

}
