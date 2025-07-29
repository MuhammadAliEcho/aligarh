<?php
return [
	'general' => [
		'name'             => 'Aligarh SMS',
		'title'            => '(campus I)',
		'address'          => 'ABC Address',
		'student_capacity' => 500,
		'contact_no'       => '5855553116',
		'email'            => 'aligarh@admin.com',
		'validity'         => '2030-01-01',
		'available_sms'		=> 3042,
		'sms_validity' 		=> '2030-01-01',
		'next_chalan_no' 		=> 1,
		'bank' => [
			'name'       => 'Abc Bank',
			'address'    => 'Abc Branch',
			'account_no' => 'AB62SCBL0000000000454545',
		]
	],
	'smtp' => [
		// Add your SMTP settings here
		'mailer'     => null,
		'host'     => null,
		'port'     => null,
		'username' => null,
		'password' => null,
		'encryption' => null,  // or 'ssl'
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
