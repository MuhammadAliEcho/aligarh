<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmployeeAttendance extends Model
{
	protected $fillable = ['date', 'employee_id', 'status'];

	protected $casts = [
		'status'		=>	'boolean'
	];

}
