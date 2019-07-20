<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TeacherAttendance extends Model
{
	protected $fillable = ['date', 'teacher_id', 'status'];

	protected $casts = [
		'status'		=>	'boolean'
	];
}
