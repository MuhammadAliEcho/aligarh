<?php

namespace App\Http\Controllers\Landlord\Api;

use App\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class NinjaClientWebHookController extends Controller
{
    public function create(Request $request)
    {
        if ($response = $this->validateTenants($request)) return $response;

        $data = $request->all();

        // Get tenant_id from custom_value2
        $tenantId = $data['custom_value2'];

        // Decode custom_value3 (tenant config)
        $customValue3 = $data['custom_value3'] ?? '{}';
        $customData = is_array($customValue3)
            ? $customValue3
            : json_decode($customValue3, true);

        if (!is_array($customData)) {
            $customData = [];
        }


        $customData['systemInfo'] = array_merge($customData['systemInfo'] ?? [], config('systemInfo'));

        // Check if tenant already exists
        $existingTenant = Tenant::find($tenantId);
        if ($existingTenant) {
            return response()->json([
                'message' => 'Tenant already exists.',
                'tenant' => $existingTenant->load('domains'),
            ], 200); // Changed from 409 to 200 since it's not really an error
        }

        // try {
        // Create Tenant - separate dedicated columns from JSON data
        $fullAddress = ($data['address1'] ?? '') . ($data['address2'] ?? '');

        $tenant = Tenant::create([
            'id'                => $tenantId,
            'name'              => $tenantId,
            'ninja_id'          => $data['id'],
            'active'            => ($data['archived_at'] ?? 1) == 0 ? 1 : 0,
            'contact_name'      => $data['name'] ?? null,
            'contact_number'    => $data['phone'] ?? null,
            'address'           => $fullAddress,
            'data'              => $customData,
        ]);

        // Log::info('Created tenant record:', $tenant->toArray());

        // Parse domain from website
        $domainName = parse_url($data['website'] ?? '', PHP_URL_HOST);

        if ($domainName) {
            $tenant->domains()->create([
                'domain' => $domainName,
                'config' => json_encode([
                    'tenancy_db_name' => $customData['tenancy_db_name'] ?? null,
                ]),
            ]);
        }

        return response()->json([
            'message' => 'Tenant and domain created successfully.',
            'tenant' => $tenant->load('domains'),
        ], 201);
        // } catch (QueryException $e) {
        //     Log::error('Failed to create tenant or domain', ['error' => $e->getMessage()]);

        //     return response()->json([
        //         'message' => 'Failed to create tenant or domain.',
        //         'error' => $e->getMessage(),
        //     ], 422);
        // }
    }

    public function update(Request $request)
    {
        // dd($request->all());

        if ($response = $this->validateTenants($request)) {
            return $response;
        }

        $data = $request->all();
        $tenantId = $data['custom_value2'];

        // Decode tenant config
        $customValue3 = $data['custom_value3'] ?? '{}';
        $customData = is_array($customValue3)
            ? $customValue3
            : json_decode($customValue3, true);

        if (!is_array($customData)) {
            $customData = [];
        }

        // Merge in system config
        $customData['systemInfo'] = array_merge($customData['systemInfo'] ?? [], config('systemInfo'));

        // Find tenant
        $tenant = Tenant::find($tenantId);

        if (!$tenant) {
            return response()->json([
                'message' => 'Tenant not found.',
            ], 404);
        }

        // Update fields
        $tenant->id             = $data['custom_value2'] ?? $tenant->id;
        $tenant->contact_name   = $data['name'] ?? $tenant->contact_name;
        $tenant->contact_number = $data['phone'] ?? $tenant->contact_number;
        $tenant->address        = ($data['address1'] ?? '') . ($data['address2'] ?? '');

        // Update custom data
        $tenant->data = array_merge($tenant->data ?? [], $customData);

        $tenant->save();

        // Update or create domain
        $domainName = parse_url($data['website'] ?? '', PHP_URL_HOST);
        if ($domainName) {
            $tenant->domains()->updateOrCreate(
                ['domain' => $domainName],
                ['config' => json_encode([
                    'tenancy_db_name' => $customData['tenancy_db_name'] ?? null,
                ])]
            );
        }

        return response()->json([
            'message' => 'Tenant updated successfully.',
            'tenant' => $tenant->load('domains'),
        ], 200);
    }


    public function validateTenants(Request $request)
    {

        // Manual validation
        $validator = Validator::make($request->all(), [
            'name'          => 'required|string',
            'address1'      => 'required|string',
            'phone'         => 'required|string',
            'custom_value1' => 'required|string',
            'custom_value2' => 'required|string',
            'custom_value3' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $request->all();
        if ($data['custom_value1'] !== 'Aligarh') {
            return response()->json([
                'message' => 'Invalid custom_value1. Expected: Aligarh',
            ], 422);
        }


        // Get tenant_id from custom_value2
        $tenantId = $data['custom_value2'] ?? null;

        if (!$tenantId) {
            return response()->json([
                'message' => 'custom_value2 is required and will be used as tenant_id.',
            ], 422);
        }
    }
}
