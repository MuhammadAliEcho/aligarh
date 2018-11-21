<?php

namespace App;

use App\Exam;
use App\AcademicSession;
use Illuminate\Database\Eloquent\Model;

class StudentAttendance extends Model
{
	protected $fillable = ['date', 'student_id', 'status'];

	protected $casts = [
		'status'		=>	'boolean'
	];

	public function scopeGetAttendanceForExam($query, Exam $exam){
		return	$query->whereBetween('date', [$exam->getOriginal('start_date'), $exam->getOriginal('end_date')]);
	}

}
