<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class VisitorStudent extends Model
{
    protected $fillable = [
        'name',
        'session_id',
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
        'dov',
        'seeking_class',
        'created_by',
        'updated_by',
    ];


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->academic_session_id = auth()->user()->academic_session;
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
