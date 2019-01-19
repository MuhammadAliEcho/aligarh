<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SmsLog extends Model
{

	protected $table = 'sms_log';
	const UPDATED_AT = null;

	protected $fillable = ['phone_info', 'message', 'api_response', 'total_price', 'created_by'];
}
