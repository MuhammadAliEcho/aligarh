<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Guardian extends Model
{


	public function Student() {
		return $this->hasMany('App\Student', 'parent_id', 'id');
	}

}
