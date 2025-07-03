<?php
namespace APP\Http\Traits;


trait ModelHeper {

    public function scopeCurrentYear($query, $start, $end)
    {
        if (!$start || !$end) {
            $start = now()->startOfYear()->toDateString();
            $end = now()->endOfYear()->toDateString();
        }

        return $query->whereBetween('date', [$start, $end]);
    }

    public function scopeAttendenceStateTrue($query)
    {
        return $query->where('status', 1);
    }
}