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
        'privileges'    =>  'object',
        'settings'      =>  'object',
    ];

    public function NavPrivileges($id, $option) {
        return isset($this->privileges->{$id}->{$option})? $this->privileges->{$id}->{$option} : 0;
    }

    public function AcademicSession() {
        return $this->hasOne('App\AcademicSession', 'id', 'academic_session');
    }

}
