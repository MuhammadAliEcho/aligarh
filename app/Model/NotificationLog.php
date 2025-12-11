<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class NotificationLog extends Model
{
    protected $table = 'notifications_log';

    protected $fillable = [
        'type',
        'message',
        'email',
        'phone',
        'status_code',
        'created_by',
        'response',
    ];

    protected $casts = [
        'response' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_by 			= Auth::user()->id??1;

        });
        static::updating(function ($model) {
            $model->updated_by  		=   Auth::user()->id??1;
        });
    }

    public function user()
    {
        return $this->belongsTo('App\Model\User', 'created_by');
    }
}
