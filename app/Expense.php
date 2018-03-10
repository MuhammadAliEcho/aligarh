<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Expense extends Model
{

	protected function getDateAttribute($date){
		return Carbon::createFromFormat('Y-m-d', $date)->format('d-m-Y');
	}
}
