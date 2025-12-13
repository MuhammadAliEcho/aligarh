<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Larapack\ConfigWriter\Repository as ConfigWriter;
use App\Model\SystemInvoice;
use PDF;
use App\Model\NotificationsSetting;
use App\Helpers\PrintableViewHelper;
use App\Model\Role;
use App\Services\PermissionDependencyService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class SystemSettingController extends Controller
{
	protected $depService;

	public function __construct(PermissionDependencyService $depService)
	{
		$this->depService = $depService;
	}
	public function GetLog()
	{
		$logo = tenancy()->tenant->system_info['general']['logo'] ?? '';

		if (!Storage::exists($logo)) {
			abort(404, 'Image not found.');
		}
		$image = Storage::get($logo);
		$mime = Storage::mimeType($logo);
		return response($image, 200)->header('Content-Type', $mime ?? 'image/jpeg');
	}
	public function GetSetting()
	{
		$data['system_invoices']		=	SystemInvoice::all();
		$data['notifications_setting']	=	NotificationsSetting::all();

		$defaultSystemInfo = config('systemInfo', []);
		$tenantSystemInfo = tenancy()->tenant->system_info ?? [];
		$data['system_info'] = array_merge_recursive_distinct($defaultSystemInfo, $tenantSystemInfo);

		// Add permission data for Module Permissions tab (same structure as role edit page)
		$data['permissions'] = $this->getPermissions();
		$data['allowedPermissions'] = $this->depService->getTenantAllowedPermissions();
		$data['permissionLabels'] = $this->depService->buildPermissionLabelMap();

		return view('admin.system_setting', $data);
	}

	public function PrintInvoiceHistory()
	{
		$data['system_invoices']	=	SystemInvoice::all();
		$pdf = PDF::loadView(PrintableViewHelper::resolve('system_invoice_history'), $data)->setPaper('a4');
		return $pdf->stream('invoice-history-2018.pdf');
		//return $pdf->download('invoice-history-2018.pdf');
		//return view(PrintableViewHelper::resolve('system_invoice_history'), $data);
	}

	public function UpdateSetting(Request $request)
	{
		// Check if this is an integration settings update
		$section = $request->input('section');
		
		if ($section === 'integrations') {
			return $this->UpdateIntegrationSettings($request);
		}
		
		// Otherwise, proceed with general settings update
		return $this->UpdateGeneralSettings($request);
	}

	/**
	 * Update general system settings (name, title, address, bank info, logo, etc.)
	 */
	protected function UpdateGeneralSettings(Request $request)
	{
		$this->validate($request, [
			// General
			'name'           				=> 'required',
			'title'          				=> 'required',
			'email'          				=> 'nullable|email',
			'address'        				=> 'required',
			'contact_name'     				=> 'nullable|string',
			'contact_no'     				=> 'nullable',
			'bank_name'      				=> 'required',
			'bank_address'   				=> 'required',
			'bank_account_no' 				=> 'required',
			'chalan_term_and_Condition'     => 'nullable|string|max:1000',
			'logo'            				=> 'image|mimes:jpg,jpeg,png|max:100',
		]);

		$tenant = tenancy()->tenant;
		$updateData = [
				// General
				'general' => [
					'name'							=> $request->input('name'),
					'address'         				=> $request->input('address'),
					'contact_name'      			=> $request->input('contact_name'),
					'contact_no'      				=> $request->input('contact_no'),
					'contact_email'     			=> $request->input('email'),
					'title'           				=> $request->input('title'),
					'chalan_term_and_Condition'     => $request->input('chalan_term_and_Condition'),
					'bank' => [
						'name'       	=> $request->input('bank_name'),
						'address'    	=> $request->input('bank_address'),
						'account_no' 	=> $request->input('bank_account_no'),
					]
				],
		];

		$currentSystemInfo = $tenant->system_info ?? [];
		$tenant->fill([
			'system_info' => array_merge_recursive_distinct($currentSystemInfo, $updateData)
		]);

		// Handle logo upload or removal
		if ($request->input('removeImage')) {
			$this->DeleteImage($tenant);
		} elseif ($request->hasFile('logo')) {
			$this->SaveImage($tenant, $request);
		}
		$tenant->save();

		return redirect('system-setting')->with([
			'toastrmsg' => [
				'type'  => 'success',
				'title' => 'System Settings',
				'msg'   => __('modules.system_settings_update_success')
			]
		]);
	}

	/**
	 * Update integration settings (SMTP, SMS, WhatsApp)
	 */
	protected function UpdateIntegrationSettings(Request $request)
	{
		$this->validate($request, [
			// SMTP
			'smtp_mailer'    				=> 'nullable|string',
			'smtp_host'      				=> 'nullable|string',
			'smtp_port'      				=> 'nullable|numeric',
			'smtp_from_address' 			=> 'nullable|email',
			'smtp_username'  				=> 'nullable|string',
			'smtp_password'  				=> 'nullable|string',
			'smtp_encryption' 				=> 'nullable|in:tls,ssl',

			// SMS
			'sms_provider'   				=> 'nullable|in:lifetimesms',
			'sms_url'    	 				=> 'nullable|string',
			'sms_api_token'    				=> 'nullable|string',
			'sms_api_secret' 				=> 'nullable|string',
			'sms_sender'  					=> 'nullable|string',

			// WhatsApp
			'whatsapp_provider' 			=> 'nullable|in:whatsapp business',
			'whatsapp_url'    				=> 'nullable|string',
			'whatsapp_token'    			=> 'nullable|string',
			'whatsapp_phone_id' 			=> 'nullable|string',
			'whatsapp_mgs_type'				=> 'nullable|string',
		]);

		$tenant = tenancy()->tenant;
		$currentSystemInfo = $tenant->system_info ?? [];
		$updateData = [];
		
		// Check if SMTP fields are present in request (any of them)
		if ($request->has('smtp_host') || $request->has('smtp_port') || $request->has('smtp_from_address') || 
		    $request->has('smtp_username') || $request->has('smtp_password') || $request->has('smtp_mailer')) {
			$updateData['smtp'] = [
				'mailer'		=> $request->input('smtp_mailer') ?? ($currentSystemInfo['smtp']['mailer'] ?? ''),
				'host'       	=> $request->input('smtp_host') ?? ($currentSystemInfo['smtp']['host'] ?? ''),
				'port'       	=> $request->input('smtp_port') ?? ($currentSystemInfo['smtp']['port'] ?? ''),
				'from_address'  => $request->input('smtp_from_address') ?? ($currentSystemInfo['smtp']['from_address'] ?? ''),
				'username'   	=> $request->input('smtp_username') ?? ($currentSystemInfo['smtp']['username'] ?? ''),
				'password'   	=> $request->input('smtp_password') ?? ($currentSystemInfo['smtp']['password'] ?? ''),
				'encryption' 	=> $request->input('smtp_encryption') ?? ($currentSystemInfo['smtp']['encryption'] ?? ''),
			];
		}
		
		// Check if SMS fields are present in request (any of them)
		if ($request->has('sms_provider') || $request->has('sms_url') || $request->has('sms_api_token') || 
		    $request->has('sms_api_secret') || $request->has('sms_sender')) {
			$updateData['sms'] = [
				'provider'   => $request->input('sms_provider') ?? ($currentSystemInfo['sms']['provider'] ?? ''),
				'url'        => $request->input('sms_url') ?? ($currentSystemInfo['sms']['url'] ?? ''),
				'api_token'  => $request->input('sms_api_token') ?? ($currentSystemInfo['sms']['api_token'] ?? ''),
				'api_secret' => $request->input('sms_api_secret') ?? ($currentSystemInfo['sms']['api_secret'] ?? ''),
				'sender'     => $request->input('sms_sender') ?? ($currentSystemInfo['sms']['sender'] ?? ''),
			];
		}
		
		// Check if WhatsApp fields are present in request (any of them)
		if ($request->has('whatsapp_provider') || $request->has('whatsapp_url') || $request->has('whatsapp_token') || 
		    $request->has('whatsapp_phone_id') || $request->has('whatsapp_mgs_type')) {
			$updateData['whatsapp'] = [
				'provider'    => $request->input('whatsapp_provider') ?? ($currentSystemInfo['whatsapp']['provider'] ?? ''),
				'url'         => $request->input('whatsapp_url') ?? ($currentSystemInfo['whatsapp']['url'] ?? ''),
				'api_token'   => $request->input('whatsapp_token') ?? ($currentSystemInfo['whatsapp']['api_token'] ?? ''),
				'phone_id'    => $request->input('whatsapp_phone_id') ?? ($currentSystemInfo['whatsapp']['phone_id'] ?? ''),
				'type'        => $request->input('whatsapp_mgs_type') ?? ($currentSystemInfo['whatsapp']['type'] ?? ''),
			];
		}

		// Save the updated data
		if (!empty($updateData)) {
			$tenant->fill([
				'system_info' => array_merge_recursive_distinct($currentSystemInfo, $updateData)
			]);
			$tenant->save();
		}

		return redirect('system-setting')->with([
			'toastrmsg' => [
				'type'  => 'success',
				'title' => __('modules.integrations'),
				'msg'   => __('modules.system_settings_update_success')
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


	protected function SaveImage($tenant, $request)
	{
		$file = $request->file('logo');

		if (isset($tenant->system_info['general']['logo']) && Storage::disk('public')->exists($tenant->system_info['general']['logo'])) {
			Storage::disk('public')->delete($tenant->system_info['general']['logo']);
		}
		$extension = $file->getClientOriginalExtension();
		$filename = 'logo_' . $tenant->id . '.' . $extension;
		Storage::disk('public')->put($filename, File::get($file));
		$systemInfo = $tenant->system_info ?? [];
		$systemInfo['general']['logo'] = $filename; // Store as: /logo_demo.jpg
		$tenant->system_info = $systemInfo;
	}

	protected function DeleteImage($tenant)
	{
		if (isset($tenant->system_info['general']['logo']) && Storage::disk('public')->exists($tenant->system_info['general']['logo'])) {
			Storage::disk('public')->delete($tenant->system_info['general']['logo']);
		}
		$systemInfo = $tenant->system_info ?? [];
		if (isset($systemInfo['general'])) {
			$systemInfo['general']['logo'] = null;
		}
		$tenant->system_info = $systemInfo;
	}

	/**
	 * Update tenant's allowed module permissions
	 */
	public function UpdateModulePermissions(Request $request)
	{
		$this->validate($request, [
			'permissions' => 'nullable|array',
			'permissions.*' => 'string'
		]);

		$tenant = tenancy()->tenant;
		$selectedPermissions = $request->input('permissions', []);
		
		// Get current system_info
		$currentSystemInfo = $tenant->system_info ?? [];
		
		// Update allowed_module_permissions array
		$currentSystemInfo['allowed_module_permissions'] = $selectedPermissions;
		
		
		// Save back to tenant
		$tenant->fill(['system_info' => $currentSystemInfo]);
		$tenant->save();

		// call another method to update all roles' permissions based on new allowed tenant permissions
		$this->syncRolePermissionsWithTenantAllowed();

		return redirect('system-setting')->with([
			'toastrmsg' => [
				'type'  => 'success',
				'title' => __('modules.module_permissions'),
				'msg'   => __('modules.module_permissions_update_success')
			]
		]);
	}

	/**
	 * Update all roles' permissions based on tenant's allowed module permissions
	 * Removes any permissions from roles that are no longer allowed by tenant
	 */
	protected function syncRolePermissionsWithTenantAllowed()
	{
		$allowedPermissions = $this->depService->getTenantAllowedPermissions();
		
		// Get all roles except Developer
		$roles = Role::where('name', '!=', 'Developer')->get();
		
		foreach ($roles as $role) {
			// Get current role permissions
			$currentPermissions = $role->permissions->pluck('name')->toArray();
			
			// Filter to keep only permissions that are still allowed by tenant
			$filteredPermissions = array_intersect($currentPermissions, $allowedPermissions);
			
			// Sync the filtered permissions back to the role
			$role->syncPermissions($filteredPermissions);
		}
	}

	/**
	 * Get all permissions without filtering (show all for tenant admin)
	 */
	private function getPermissions(): array
	{
		return config('permission.permissions', []);
	}

}
