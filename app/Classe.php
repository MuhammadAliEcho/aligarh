<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Classe extends Model
{



	public function Section() {
		return $this->hasMany('App\Section', 'class_id')->orderBy('id');
	}

/*
  public function teacher(){
    return $this->belongsTo('App\Teacher');
  }
*/
}
