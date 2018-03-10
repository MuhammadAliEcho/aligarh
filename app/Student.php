<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Student extends Model {

	public function Guardian() {
		return $this->hasOne('App\Guardian', 'id', 'parent_id');
	}

	public function Std_Class() {
		return $this->hasOne('App\Classe', 'id', 'class_id');
	}

	public function Section() {
		return $this->hasOne('App\Section', 'id', 'section_id');
	}

	public function getDateOfBirthAttribute($date){
		return Carbon::createFromFormat('Y-m-d', $date)->format('d/m/Y');
	}

	public function getDateOfAdmissionAttribute($date){
		return Carbon::createFromFormat('Y-m-d', $date)->format('d/m/Y');
	}

	public function AdditionalFee(){
		return $this->hasMany('App\AdditionalFee');
	}

}