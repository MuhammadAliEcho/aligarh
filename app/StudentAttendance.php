<?php

namespace App;

use App\Exam;
use App\AcademicSession;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Http\Traits\ModelHeper;



class StudentAttendance extends Model
{
	use ModelHeper;

	protected $fillable = ['date', 'student_id', 'status'];

	protected $casts = [
		'status'		=>	'boolean'
	];

	public function scopeGetAttendanceForExam($query, Exam $exam){
		return	$query->whereBetween('date', [$exam->getOriginal('start_date'), $exam->getOriginal('end_date')]);
	}

	public function scopeGroupByMonth($query)
	{
    return $query->select(
        DB::raw('MONTH(date) as month'),
        DB::raw('YEAR(date) as year'),
        DB::raw('COUNT(*) as total'),
        DB::raw("SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as present"),
        DB::raw("SUM(CASE WHEN status = 0 THEN 1 ELSE 0 END) as absent")
    )
    ->groupBy(DB::raw('YEAR(date), MONTH(date)'))
    ->orderBy(DB::raw('YEAR(date), MONTH(date)'));
	}
}
