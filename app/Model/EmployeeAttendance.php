<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\ModelHeper;

class EmployeeAttendance extends Model
{
	use ModelHeper;
	
	protected $fillable = ['date', 'employee_id', 'status'];

	protected $casts = [
		'status'		=>	'boolean'
	];

}
