<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class VisitorStudent extends Model
{
    protected $fillable = [
        'name',
        'session_id',
        'student_id',
        'father_name',
        'class_id',
        'email',
        'religion',
        'gender',
        'phone',
        'address',
        'place_of_birth',
        'guardian_relation',
        'date_of_birth',
        'last_school',
        'date_of_visiting',
        'seeking_class',
        'created_by',
        'updated_by',
    ];


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->session_id = Auth::user()->academic_session;
            $model->created_by = Auth::user()->id??2;
        });
    }

    public function StdClass()
    {
        return $this->hasOne('App\Classe', 'id', 'class_id');
    }

    public function session()
    {
        return $this->belongsTo('App\Session', 'session_id');
    }
}
