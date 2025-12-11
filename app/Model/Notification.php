<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'notification',
        'link',
        'is_read',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo('App\Model\User', 'user_id');
    }

    public function scopeSelfUser($query)
    {
        return $query->where('user_id', auth()->user()->id);
    }
}
