<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Exam extends Model
{
	

	protected function getStartDateAttribute($start_date){
		return Carbon::createFromFormat('Y-m-d', $start_date)->format('d/m/Y');
	}

	protected function getEndDateAttribute($end_date){
		return Carbon::createFromFormat('Y-m-d', $end_date)->format('d/m/Y');
	}


}
