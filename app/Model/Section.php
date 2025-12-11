<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Section extends Model
{

	public function Classe() {
		return $this->belongsTo('App\Model\Classe', 'class_id');
	}

	public function Teacher() {
		return $this->belongsTo('App\Model\Teacher');
	}

	public function Students(){
		return $this->hasMany('App\Model\Student');
	}

	public function AddDefaultSection($classid, $teacherid){
		$this->name = 'Section A';
		$this->nick_name = 'A';
		$this->class_id = $classid;
		$this->teacher_id = $teacherid;
		$this->created_by	=	Auth::user()->id;
		$this->save();
	}
}
