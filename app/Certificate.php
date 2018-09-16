<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
	protected $fillable = [
		'student_id', 'title', 'certificate'
	];

	public function Student(){
		return $this->belongsTo('App\Student');
	}
}
