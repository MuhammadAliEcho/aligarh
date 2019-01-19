<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{


	public function User(){
		return $this->belongsTo('App\User');
	}


	public function scopeHaveCellNo($query){
		return $query->where('phone', 'NOT LIKE', '21%')->whereRaw('LENGTH(phone) = 10');
	}

}
