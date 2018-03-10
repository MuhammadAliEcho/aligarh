<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Section extends Model
{

	public function Class() {
		return $this->belongsTo('App\Classe', 'class_id');
	}

	public function Teacher() {
		return $this->belongsTo('App\Teacher');
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
