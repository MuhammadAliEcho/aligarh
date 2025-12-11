<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Classe extends Model
{


	public function Section() {
		return $this->hasMany('App\Model\Section', 'class_id')->orderBy('id');
	}

	public function Teacher(){
		return $this->belongsTo('App\Model\Teacher');
	}

	public function Subject(){
		return $this->hasMany('App\Model\Subject', 'class_id');
	}

	public function Students(){
		return $this->hasMany('App\Model\Student', 'class_id');
	}
	public function ActiveStudents(){
		return $this->hasMany('App\Model\Student', 'class_id')->active();
	}
/*
  public function teacher(){
    return $this->belongsTo('App\Model\Teacher');
  }
*/
	public function scopeIdOrderAsc($query){
		return $this->scopeNumericOrderAsc(
			$query->orderBy('id', 'asc')
		);
	}
	
	public function scopeNumericOrderAsc($query){
        return $query->orderBy('numeric_name', 'asc');
    }
}
