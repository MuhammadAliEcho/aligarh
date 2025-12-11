<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
	protected $fillable = [
		'student_id', 'title', 'certificate', 'created_by', 'updated_by'
	];

	public function Student(){
		return $this->belongsTo('App\Model\Student');
	}
}
