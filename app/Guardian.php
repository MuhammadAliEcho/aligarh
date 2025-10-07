<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Guardian extends Model
{


	public function Student() {
		return $this->hasMany('App\Student');
	}

	public function scopeHaveCellNo($query){
		return $query->where('phone', 'NOT LIKE', '21%')->whereRaw('LENGTH(phone) = 10');
	}

	public function ActiveStudents(){
		return $this->hasMany('App\Student', 'guardian_id')->active();
	}

}
