<?php

namespace App\Model;

use App\Model\Quiz;
use App\Model\Student;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class QuizResult extends Model
{
    protected $table = 'quiz_result';
    protected $keyType = 'string';
    public $incrementing = false; 

    protected $fillable = [
        'id',
        'quiz_id',
        'student_id',
        'obtain_marks',
        'present',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    public function quiz()
    {
        return $this->hashOne(Quiz::class);
    }
}
