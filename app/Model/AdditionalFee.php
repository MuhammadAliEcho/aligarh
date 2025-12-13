<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AdditionalFee extends Model
{

	protected $casts = [
		'onetime'		=>	'boolean',
		'active'		=>	'boolean',
	];

	public function Student(){
		return $this->belongsTo('App\Model\Student');
	}

	public function scopeActive($query){
		return $query->where('active', 1);
	}

}
