<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SmsLog extends Model
{

	protected $table = 'sms_log';
	const UPDATED_AT = null;

	protected $fillable = ['phone_info', 'message', 'api_response', 'total_price', 'created_by'];

	protected $casts	=	[
		'phone_info'	=>	'object',
		'api_response'	=>	'object'
	];

	public function User(){
		return $this->belongsTo('App\Model\User', 'created_by');
	}

}
