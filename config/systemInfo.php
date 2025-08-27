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
		'mailer'     => 'smtp',
		'host'     => 'host.docker.internal',
		'port'     => '1025',
		'username' => 'null',
		'password' => null,
		'encryption' => null,  // or 'ssl'
		// 'from_email' => 'no-reply@example.com',
		// 'from_name'  => 'Aligarh SMS',
	],
	'sms' => [
		// Add your SMS settings here
		'provider'     => 'lifetimesms',  // Example provider
		'url'      => 'https://lifetimesms.com/json',
		'api_token'      => '8cbd9538d0393dc97bfffd37c028cafcbcdecb9971',
		'api_secret'   => 'pumH3vVu0I8LvYzr2KAmSHW6ijZuaWmh',
		'sender'    => 'Aligarh',
	],
	'whatsapp' => [
		// Add your WhatsApp settings here
		'provider'     => 'whatsapp business',  // Example provider
		'url'      => 'https://graph.facebook.com/v19.0/',
		'api_token'    => 'e647dad1-a318-49d7-b381-3102bc5c5b27',
		'phone_id' => '1234567890',
		'type' => 'text',
	],
];
