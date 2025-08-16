<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotificationsSetting extends Model
{
     protected $table = 'notifications_settings';

    protected $fillable = [
        'name',
        'mail',
        'sms',
        'whatsapp',
    ];

    protected $casts = [
        'mail' => 'boolean',
        'sms' => 'boolean',
        'whatsapp' => 'boolean',
    ];
}
