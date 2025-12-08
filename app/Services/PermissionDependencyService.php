<?php

namespace App\Services;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;

/**
 * Permission Dependency Service
 * 
 * Handles:
 * 1. Auto-cascading dependent permissions (Option 2)
 * 2. Validating permission completeness (Option 4)
 * 3. Generating warnings for incomplete permission sets
 * 4. Auditing dependency changes
 */
class PermissionDependencyService
{
    /**
     * Get all dependencies for a permission
     * 
     * @param string $permissionName
     * @return array
     */
    public function getDependencies($permissionName): array
    {
        $dependencies = config('permission_dependencies', []);
        return $dependencies[$permissionName] ?? [];
    }
    
    /**
     * Get all permissions that depend on a given permission
     * (reverse dependencies)
     * 
     * @param string $permissionName
     * @return array
     */
    public function getReverseDependencies($permissionName): array
    {
        $dependencies = config('permission_dependencies', []);
        $reverseDeps = [];
        
        foreach ($dependencies as $parent => $children) {
            if (in_array($permissionName, $children)) {
                $reverseDeps[] = $parent;
            }
        }
        
        return $reverseDeps;
    }
    
    /**
     * Recursively get all dependent permissions (tree)
     * 
     * @param string $permissionName
     * @param array $visited
     * @return array
     */
    public function getAllDependencies($permissionName, $visited = []): array
    {
        if (in_array($permissionName, $visited)) {
            return [];
        }
        
        $visited[] = $permissionName;
        $allDeps = [];
        
        foreach ($this->getDependencies($permissionName) as $dep) {
            $allDeps[] = $dep;
            $allDeps = array_merge($allDeps, $this->getAllDependencies($dep, $visited));
        }
        
        return array_unique($allDeps);
    }
    
    /**
     * Grant permission to role with auto-cascading dependencies (Option 2)
     * 
     * @param Role $role
     * @param string $permissionName
     * @return array ['granted' => [], 'already_had' => [], 'failed' => []]
     */
    public function grantPermissionWithDependencies(Role $role, $permissionName): array
    {
        $result = [
            'granted' => [],
            'already_had' => [],
            'failed' => []
        ];
        
        // Get all dependencies including the permission itself
        $permissions = array_merge([$permissionName], $this->getAllDependencies($permissionName));
        $permissions = array_unique($permissions);
        
        foreach ($permissions as $perm) {
            try {
                $permission = Permission::where('name', $perm)->first();
                
                if (!$permission) {
                    $result['failed'][] = $perm;
                    Log::warning("PermissionDependencyService: Permission '{$perm}' not found for role '{$role->name}'");
                    continue;
                }
                
                // Check if already has permission
                if ($role->hasPermissionTo($permission)) {
                    $result['already_had'][] = $perm;
                } else {
                    $role->givePermissionTo($permission);
                    $result['granted'][] = $perm;
                    Log::info("PermissionDependencyService: Granted '{$perm}' to role '{$role->name}'");
                }
            } catch (\Exception $e) {
                $result['failed'][] = $perm;
                Log::error("PermissionDependencyService: Error granting '{$perm}' to role '{$role->name}': {$e->getMessage()}");
            }
        }
        
        return $result;
    }
    
    /**
     * Revoke permission from role with auto-cascading (Option 2)
     * Also revokes permissions that depend on this one
     * 
     * @param Role $role
     * @param string $permissionName
     * @return array ['revoked' => [], 'dependent_revoked' => [], 'failed' => []]
     */
    public function revokePermissionWithDependencies(Role $role, $permissionName): array
    {
        $result = [
            'revoked' => [],
            'dependent_revoked' => [],
            'failed' => []
        ];
        
        // Get reverse dependencies (permissions that depend on this one)
        $dependents = $this->getReverseDependencies($permissionName);
        
        // First, revoke all dependent permissions
        foreach ($dependents as $dependent) {
            try {
                $permission = Permission::where('name', $dependent)->first();
                
                if ($permission && $role->hasPermissionTo($permission)) {
                    $role->revokePermissionTo($permission);
                    $result['dependent_revoked'][] = $dependent;
                    Log::info("PermissionDependencyService: Revoked dependent '{$dependent}' from role '{$role->name}'");
                }
            } catch (\Exception $e) {
                $result['failed'][] = $dependent;
                Log::error("PermissionDependencyService: Error revoking dependent '{$dependent}': {$e->getMessage()}");
            }
        }
        
        // Then revoke the main permission
        try {
            $permission = Permission::where('name', $permissionName)->first();
            
            if ($permission) {
                $role->revokePermissionTo($permission);
                $result['revoked'][] = $permissionName;
                Log::info("PermissionDependencyService: Revoked '{$permissionName}' from role '{$role->name}'");
            }
        } catch (\Exception $e) {
            $result['failed'][] = $permissionName;
            Log::error("PermissionDependencyService: Error revoking '{$permissionName}': {$e->getMessage()}");
        }
        
        return $result;
    }
    
    /**
     * Validate permission completeness (Option 4)
     * Check if a role has all required dependencies for granted permissions
     * 
     * @param Role $role
     * @return array ['complete' => [], 'incomplete' => []]
     */
    public function validatePermissionCompleteness(Role $role): array
    {
        $rolePermissions = $role->permissions->pluck('name')->toArray();
        $dependencies = config('permission_dependencies', []);
        
        $result = [
            'complete' => [],
            'incomplete' => []
        ];
        
        foreach ($rolePermissions as $permission) {
            $deps = $dependencies[$permission] ?? [];
            
            if (empty($deps)) {
                $result['complete'][] = $permission;
                continue;
            }
            
            $missingDeps = array_diff($deps, $rolePermissions);
            
            if (empty($missingDeps)) {
                $result['complete'][] = $permission;
            } else {
                $result['incomplete'][$permission] = $missingDeps;
            }
        }
        
        return $result;
    }
    
    /**
     * Auto-fix incomplete permissions (Option 4)
     * Automatically grant missing dependencies
     * 
     * @param Role $role
     * @return array ['fixed' => [], 'failed' => []]
     */
    public function fixIncompletePermissions(Role $role): array
    {
        $validation = $this->validatePermissionCompleteness($role);
        $result = ['fixed' => [], 'failed' => []];
        
        foreach ($validation['incomplete'] as $permission => $missingDeps) {
            foreach ($missingDeps as $missingDep) {
                try {
                    $depPermission = Permission::where('name', $missingDep)->first();
                    
                    if ($depPermission && !$role->hasPermissionTo($depPermission)) {
                        $role->givePermissionTo($depPermission);
                        $result['fixed'][] = [
                            'parent' => $permission,
                            'dependency' => $missingDep
                        ];
                        Log::info("PermissionDependencyService: Auto-fixed missing '{$missingDep}' for role '{$role->name}' (required by '{$permission}')");
                    }
                } catch (\Exception $e) {
                    $result['failed'][] = $missingDep;
                    Log::error("PermissionDependencyService: Error fixing '{$missingDep}': {$e->getMessage()}");
                }
            }
        }
        
        return $result;
    }
    
    /**
     * Get permission dependency tree (for UI display)
     * 
     * @return array
     */
    public function getDependencyTree(): array
    {
        return config('permission_dependencies', []);
    }
    
    /**
     * Generate audit log entry for permission changes
     * 
     * @param Role $role
     * @param string $action ('grant'|'revoke')
     * @param array $permissions
     * @param array $auditData
     * @return void
     */
    public function auditPermissionChange(Role $role, $action, $permissions, $auditData = []): void
    {
        Log::channel('permissions')->info("Permission {$action} for role '{$role->name}'", [
            'role_id' => $role->id,
            'action' => $action,
            'permissions' => $permissions,
            'user_id' => auth()?->user()?->id,
            'timestamp' => now(),
            'audit_data' => $auditData
        ]);
    }
}
