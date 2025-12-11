<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Role;
use App\Services\PermissionDependencyService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    protected $depService;

    public function __construct(PermissionDependencyService $depService)
    {
        $this->depService = $depService;
    }

    /**
     * Display roles listing and create form
     * Handles DataTables AJAX requests for role list
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->getRolesDataTable();
        }

        return view('admin.roles', [
            'content' => null,
            'permissions' => $this->getPermissions(),
            'permissionLabels' => $this->depService->buildPermissionLabelMap(),
        ]);
    }

    /**
     * Create a new role with permissions
     * Auto-grants dependent permissions based on service logic
     */
    public function create(Request $request)
    {
        $this->validateRoleCreation($request);

        DB::beginTransaction();
        try {
            $role = Role::create([
                'name' => $request->input('name'),
                'guard_name' => 'web'
            ]);

            $allPermissions = $this->resolvePermissionsWithDependencies(
                $request->input('permissions', [])
            );

            $role->syncPermissions($allPermissions);

            // Validate completeness
            $this->depService->validatePermissionCompleteness($role);

            DB::commit();

            return $this->successResponse('roles', __('modules.common_register_success'), 'Role Registration');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->logError($e);
            return $this->errorResponse('roles', __('modules.roles_update_error'), 'Roles');
        }
    }

    /**
     * Show the edit form for a role
     */
    public function edit($id)
    {
        $role = Role::notDeveloper()->findOrFail($id);
        $rolePermissions = $role->permissions->pluck('name')->toArray();

        return view('admin.edit_role', [
            'role' => $role,
            'rolePermissions' => $rolePermissions,
            'permissions' => $this->getPermissions(),
            'permissionLabels' => $this->depService->buildPermissionLabelMap(),
        ]);
    }

    /**
     * Update role permissions
     * Optionally sync permissions to all roles (Developer only)
     */
    public function update(Request $request, $id)
    {
        $this->validatePermissions($request);

        DB::beginTransaction();
        try {
            $role = Role::notDeveloper()->findOrFail($id);

            $allPermissions = $this->resolvePermissionsWithDependencies(
                $request->input('permissions', [])
            );

            $role->syncPermissions($allPermissions);

            // Sync to all roles if requested (Developer feature)
            if ($request->filled('sync_permissions')) {
                $this->syncPermissionsToAllRoles($allPermissions);
            }

            // Validate completeness
            $this->depService->validatePermissionCompleteness($role);

            DB::commit();

            return $this->successResponse('roles', __('modules.roles_update_success'), 'Role Updated');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->logError($e);
            return $this->errorResponse('roles', 'There was an issue while Updating Role', 'Roles');
        }
    }

    // ==================== Private Helper Methods ====================

    /**
     * Get DataTable for roles listing
     */
    private function getRolesDataTable()
    {
        return DataTables::eloquent(
            Role::select('id', 'name', 'created_at')->notDeveloper()
        )
            ->editColumn('created_at', function ($role) {
                return $role->created_at->format('Y-m-d');
            })
            ->make(true);
    }

    /**
     * Validate role creation request
     */
    private function validateRoleCreation(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions.*' => 'exists:permissions,name',
        ], [
            'permissions.*.exists' => 'The selected permission is invalid.',
        ]);
    }

    /**
     * Validate permissions in update request
     * Includes pre-validation to catch non-existent permissions
     */
    private function validatePermissions(Request $request)
    {
        $submitted = $request->input('permissions', []);

        // Pre-validation: Check if permissions exist in database
        $valid = DB::table('permissions')
            ->whereIn('name', $submitted)
            ->pluck('name')
            ->toArray();

        $invalid = array_diff($submitted, $valid);

        if (!empty($invalid)) {
            Log::warning('Invalid permissions submitted', [
                'invalid_permissions' => $invalid,
                'user_id' => auth()->id(),
                'submitted_permissions' => $submitted,
            ]);

            return back()->withErrors([
                'permissions' => 'These permissions do not exist: ' . implode(', ', $invalid),
            ])->withInput();
        }

        // Formal validation
        $request->validate([
            'permissions.*' => 'exists:permissions,name',
        ], [
            'permissions.*.exists' => 'The selected permission is invalid.',
        ]);
    }

    /**
     * Resolve permissions with their dependencies
     * Uses PermissionDependencyService to auto-grant required permissions
     */
    private function resolvePermissionsWithDependencies(array $permissions): array
    {
        $allPermissions = [];

        foreach ($permissions as $permission) {
            $allPermissions[] = $permission;
            $allPermissions = array_merge(
                $allPermissions,
                $this->depService->getAllDependencies($permission)
            );
        }

        return array_unique($allPermissions);
    }

    /**
     * Sync permissions to all non-Developer roles
     * (Developer-only feature)
     */
    private function syncPermissionsToAllRoles(array $permissions)
    {
        $roles = Role::notDeveloper()->get();

        foreach ($roles as $role) {
            $role->syncPermissions($permissions);
        }
    }

    /**
     * Log exception error
     */
    private function logError(\Exception $e)
    {
        Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());
    }

    /**
     * Success redirect response with toast message
     */
    private function successResponse(string $route, string $message, string $title)
    {
        return redirect($route)->with([
            'toastrmsg' => [
                'type' => 'success',
                'title' => $title,
                'msg' => $message
            ]
        ]);
    }

    /**
     * Error redirect response with toast message
     */
    private function errorResponse(string $route, string $message, string $title)
    {
        return redirect($route)->with([
            'toastrmsg' => [
                'type' => 'error',
                'title' => $title,
                'msg' => $message
            ]
        ]);
    }

    /**
     * Get permissions filtered by tenant-allowed permissions
     * Developer role bypasses tenant restrictions
     */
    private function getPermissions(): array
    {
        $allPermissions = config('permission.permissions', []);
        $isDeveloper = auth()->user()->hasRole('Developer');
        
        // Filter permissions based on tenant's allowed permissions
        return $this->depService->filterPermissionsByTenant($allPermissions, $isDeveloper);
    }
}
