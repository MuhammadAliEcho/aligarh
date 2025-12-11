<?php
namespace App\Http\Traits;

use App\Model\AttendanceLeave;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait HasLeave
{
    public function leaveOnDate(): MorphOne
    {
        return $this->morphOne(AttendanceLeave::class, 'person');
    }

    public function leaveOn($date): MorphOne
    {
        return $this->leaveOnDate()->where('from_date', '<=', $date)->where('to_date', '>=', $date);
    }

    public static function withLeaveOn($date)
    {
        return static::with(['leaveOnDate' => fn ($query) =>
            $query->where('from_date', '<=', $date)->where('to_date', '>=', $date)
        ]);
    }
}