<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Voucher extends Model
{

	public function Details(){
		return $this->hasMany('App\VoucherDetail', 'voucher_id', 'id');
	}

	public function Vendor(){
		return $this->hasOne('App\Vendor', 'id', 'vendor_id');
	}

	public function getVoucherDateAttribute($date){
		return Carbon::createFromFormat('Y-m-d', $date)->format('d/m/Y');
	}

}
