<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UserPrivilege extends Model
{


	protected $fillable = [
		'user_id',
		'privileges',
		'allow_session'
		];

	public $timestamps = false;

	protected $casts = [
		'privileges'    =>  'object',
		'allow_session'    =>  'object',
	];

	public function user() {
		return $this->belongsTo('App\Model\User');
	}

	public function NavPrivileges($id, $option) {
		return isset($this->privileges->{$id}->{$option})? $this->privileges->{$id}->{$option} : 0;
	}

}
