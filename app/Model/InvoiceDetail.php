<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class InvoiceDetail extends Model
{

	protected	$fillable	=	[
		'invoice_id',	'fee_name',	'amount'
	];

}
