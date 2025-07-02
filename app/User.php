<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{

	use HasApiTokens, Notifiable, HasRoles;
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'name', 'email', 'password', 'foreign_id', 'user_type', 'active', 'academic_session', 'settings'
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

	protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_by = Auth::user()->id??2;
        });
        static::updating(function ($model) {
            $model->updated_by  =   Auth::user()->id??2;
        });
    }

	public function getprivileges(){
		return $this->hasOne('App\UserPrivilege');
	}

	public function AcademicSession() {
		return $this->hasOne('App\AcademicSession', 'id', 'academic_session');
	}

	public function scopeStaff($query){
		return	$query->whereIn('user_type', ['employee', 'teacher']);
	}

	public function scopeNotDeveloper($query){
		return $query->whereKeyNot(1);
	}
}
