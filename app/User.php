<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'name', 'email', 'password'
	];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'password', 'remember_token',
	];

	protected $casts = [
		'settings'      =>  'object',
	];

	public function getprivileges(){
		return $this->hasOne('App\UserPrivilege');
	}

	public function AcademicSession() {
		return $this->hasOne('App\AcademicSession', 'id', 'academic_session');
	}

}
