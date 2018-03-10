<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VoucherDetail extends Model
{

	public function Item(){
		return $this->hasOne('App\Item', 'id', 'item_id');
	}

}
