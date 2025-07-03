<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\ModelHeper;

class TeacherAttendance extends Model
{
	use ModelHeper;
	
	protected $fillable = ['date', 'teacher_id', 'status'];

	protected $casts = [
		'status'		=>	'boolean'
	];
}
