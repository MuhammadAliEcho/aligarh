<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Larapack\ConfigWriter\Repository as ConfigWriter;
use App\SystemInvoice;
use PDF;
use App\NotificationsSetting;

class SystemSettingController extends Controller
{
	public function GetSetting()
	{
		$data['system_invoices']	=	SystemInvoice::all();
		$data['notifications_setting']	=	NotificationsSetting::all();
		$data['system_info']	=	(tenancy()->tenant->system_info);
		return view('admin.system_setting', $data);
	}

	public function PrintInvoiceHistory()
	{
		$data['system_invoices']	=	SystemInvoice::all();
		$pdf = PDF::loadView('admin.printable.system_invoice_history', $data)->setPaper('a4');
		return $pdf->stream('invoice-history-2018.pdf');
		//return $pdf->download('invoice-history-2018.pdf');
		//return view('admin.printable.system_invoice_history', $data);
	}

	public function UpdateSetting(Request $request)
	{

		$this->validate($request, [
			// General
			'name'           => 'required',
			'title'          => 'required',
			'email'          => 'nullable|email',
			'address'        => 'required',
			'contact_no'     => 'nullable',
			'bank_name'      => 'required',
			'bank_address'   => 'required',
			'bank_account_no' => 'required',

			// SMTP
			'smtp_host'      => 'nullable|string',
			'smtp_port'      => 'nullable|numeric',
			'smtp_username'  => 'nullable|string',
			'smtp_password'  => 'nullable|string',
			'smtp_encryption' => 'nullable|in:tls,ssl',

			// SMS
			'sms_provider'   => 'nullable|in:lifetimesms',
			'sms_url'    	 => 'nullable|string',
			'sms_api_token'    => 'nullable|string',
			'sms_api_secret' => 'nullable|string',
			'sms_sender'  => 'nullable|string',

			// WhatsApp
			'whatsapp_provider' => 'nullable|in:whatsapp business',
			'whatsapp_url'    => 'nullable|string',
			'whatsapp_token'    => 'nullable|string',
			'whatsapp_phone_id' => 'nullable|string',
		]);

		$tenant = tenancy()->tenant;
		$updateData = [
				// General
				'general' => [
					'name'				=> $request->input('name'),
					'address'         	=> $request->input('address'),
					'contact_name'      	=> $request->input('contact_name'),
					'contact_no'      	=> $request->input('contact_no'),
					'contact_email'           	=> $request->input('contact_email'),
					'title'           	=> $request->input('title'),
					'bank' => [
						'name'       	=> $request->input('bank_name'),
						'address'    	=> $request->input('bank_address'),
						'account_no' 	=> $request->input('bank_account_no'),
					]
				],

				// SMTP as nested array
				'smtp' => [
					'mailer'		=> $request->input('smtp_mailer'),
					'host'       	=> $request->input('smtp_host'),
					'port'       	=> $request->input('smtp_port'),
					'username'   	=> $request->input('smtp_username'),
					'password'   	=> $request->input('smtp_password'),
					'encryption' 	=> $request->input('smtp_encryption'),
				],

				// SMS as nested array
				'sms' => [
					'provider'   => $request->input('sms_provider'),
					'url'    => $request->input('sms_url'),
					'api_token'    => $request->input('sms_api_token'),
					'api_secret' => $request->input('sms_api_secret'),
					'sender'  => $request->input('sms_sender'),
				],

				// WhatsApp as nested array
				'whatsapp' => [
					'provider'    		=> $request->input('whatsapp_provider'),
					'url' 				=> $request->input('whatsapp_url'),
					'api_token'   		=> $request->input('whatsapp_token'),
					'phone_id' 	=> $request->input('whatsapp_phone_id'),
					'type' 				=> $request->input('whatsapp_mgs_type'),
				],
		];

		$currentSystemInfo = $tenant->system_info ?? [];
		// $currentSystemInfo = config('systemInfo');
		$tenant->fill([
			'system_info' => array_merge_recursive_distinct($currentSystemInfo, $updateData)
		]);

		$tenant->save();

		return redirect('system-setting')->with([
			'toastrmsg' => [
				'type'  => 'success',
				'title' => 'System Settings',
				'msg'   => 'Settings Updated Successfully'
			]
		]);
	}

	public function NotificationSettings(Request $request, $id)
	{
		// Check if ID is "row" - update a specific record
		if ($id === 'row') {
			$notificationId = $request->input('id');
			$mail = $request->input('mail');
			$sms = $request->input('sms');
			$whatsapp = $request->input('whatsapp');

			$notification = NotificationsSetting::findOrFail($notificationId);

			$notification->mail = $mail;
			$notification->sms = $sms;
			$notification->whatsapp = $whatsapp;
			$notification->save();

			return response()->json([
				'message' => "Row settings updated successfully.",
			]);
		}

		$field = $request->input('field');
		$value = $request->input('value');

		if (!in_array($field, ['mail', 'sms', 'whatsapp'])) {
			return response()->json([
				'message' => 'Invalid field name.'
			], 422);
		}

		// Check if ID is "all" - update all records
		if ($id === 'all') {
			NotificationsSetting::query()->update([$field => $value]);

			return response()->json([
				'message' => "All {$field} settings updated successfully.",
				'field' => $field,
				'value' => $value
			]);
		}

		// Regular single update
		$notificationSetting = NotificationsSetting::findOrFail($id);
		$notificationSetting->$field = $value;
		$notificationSetting->save();

		return response()->json([
			'message' => 'Notification setting updated successfully.',
			'field'   => $field,
			'value'   => $value
		]);
	}
}
