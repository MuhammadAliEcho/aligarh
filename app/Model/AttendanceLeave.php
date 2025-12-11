<?php

namespace App\Model;

use App\Model\Student;
use App\Model\Teacher;
use App\Model\Employee;
use App\Model\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AttendanceLeave extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'person_id',
        'from_date',
        'to_date',
        'remarks',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'from_date' => 'date',
        'to_date' => 'date',
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

    public function person()
    {
        return $this->morphTo();
    }


    public function getFromDateAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }

    public function getToDateAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
