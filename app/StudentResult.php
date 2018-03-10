<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentResult extends Model
{
	protected $fillable = ['exam_id', 'student_id', 'subject_id', 'obtain_marks', 'remarks'];

	
}
