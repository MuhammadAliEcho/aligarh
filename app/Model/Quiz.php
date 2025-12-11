<?php

namespace App\Model;

use App\Model\Classe;
use App\Model\Section;
use App\Model\Teacher;
use App\Model\AcademicSession;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'title',
        'class_id',
        'section_id',
        'academic_session_id',
        'date',
        'teacher_id',
        'total_marks',
    ];

    protected $casts = [
        'total_marks' => 'float',
    ];

    // Automatically generate UUID on creating
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
            $model->academic_session_id = auth()->user()->academic_session;
        });
    }


    public function class()
    {
        return $this->belongsTo(Classe::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function session()
    {
        return $this->belongsTo(AcademicSession::class);
    }

    public function scopeSelfSession($query)
    {
        return $query->where('academic_session_id', auth()->user()->academic_session);
    }

    public function quizResults()
    {
        return $this->hasMany(QuizResult::class);
    }
}
