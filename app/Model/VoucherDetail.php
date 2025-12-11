<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class VoucherDetail extends Model
{

	public function Item(){
		return $this->hasOne('App\Model\Item', 'id', 'item_id');
	}

}
