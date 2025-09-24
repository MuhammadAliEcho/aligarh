<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Auth;
use App\Notifications\ResetPasswordNotification;

class User extends Authenticatable
{

	use HasApiTokens, Notifiable, HasRoles;
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'name', 'email', 'password', 'foreign_id', 'user_type', 'active', 'academic_session', 'settings', 'allow_session'
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
		'allow_session' => 	'array',
	];

	protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_by 			= Auth::user()->id??2;
            $model->settings  			=   Auth::user()->settings?? ["skin_config"=>["nav_collapse"=>""]]; // its obect it should pas array not like string.

        });
        static::updating(function ($model) {
            $model->updated_by  		=   Auth::user()->id??2;
						// whay settings update on here every edit and its cast object it should pass as array you set it as string ..
            // $model->settings  			=   ["skin_config"=>["nav_collapse"=>""]];
            // $model->settings  			=     '{"skin_config":{"nav_collapse":""}}';
            // $model->settings  			=   Auth::user()->settings?? '{"skin_config":{"nav_collapse":""}}';
        });
    }

	public function sendPasswordResetNotification($token)
	{
		$this->notify(new ResetPasswordNotification($token));
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
