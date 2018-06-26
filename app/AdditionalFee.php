<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdditionalFee extends Model
{

	protected $casts = [
		'onetime'		=>	'boolean',
		'active'		=>	'boolean',
	];

	public function Student(){
		return belongsTo('App\Atudent');
	}

}
