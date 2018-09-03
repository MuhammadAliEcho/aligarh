<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentAttendence extends Model
{
	protected $fillable = ['date', 'student_id', 'status', 'user_id'];

	protected $casts = [
		'status'		=>	'boolean'
	];

}
