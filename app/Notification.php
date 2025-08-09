<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'notification',
        'link',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
