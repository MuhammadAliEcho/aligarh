<?php
return [
	'general' => [
		'name'             				=> 'Aligarh SMS',
		'title'            				=> '(campus I)',
		'address'          				=> 'ABC Address',
		'student_capacity' 				=> 500,
		'contact_no'       				=> '5855553116',
		'contact_email'    				=> 'aligarh@admin.com',
		'validity'         				=> '2030-01-01',
		'available_sms'					=> 3042,
		'sms_validity' 					=> '2030-01-01',
		'next_chalan_no' 				=> 1,
		'chalan_term_and_Condition' 	=> '1. All Types of Fees are non refundable.
											2. Late fee of 150 will be charged after 15th of every month irrespective of holidays.
											3. Receipt will only be valid when it bears the bank stamp and signature of the designated bank officer.
											4. Fee Challan will not be valid for payment after 25th of each month.
											5. If the voucher is lost by the parent or student, Rs 70/- will be charged for duplicate receipt.
											Only cash will be acceptable',
		'logo' 				=> null,
		'bank' => [
			'name'       => 'Abc Bank',
			'address'    => 'Abc Branch',
			'account_no' => 'AB62SCBL0000000000454545',
		]
	],
	'smtp' => [
		// Add your SMTP settings here
		'mailer'     	=> null,
		'host'     		=> null,
		'port'     		=> null,
		'from_address' 	=> null,
		'username' 		=> null,
		'password' 		=> null,
		'encryption' 	=> null,  // or 'ssl'
	],
	'sms' => [
		// Add your SMS settings here
		'provider'     => null,  // Example provider
		'url'      => null,
		'api_token'      => null,
		'api_secret'   => null,
		'sender'    => null,
	],
	'whatsapp' => [
		// Add your WhatsApp settings here
		'provider'     => null,  // Example provider
		'url'      => null,
		'api_token'    => null,
		'phone_id' => null,
		'type' => null,
	],
];
