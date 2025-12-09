<?php

namespace App\Services;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;

/**
 * Permission Dependency Service
 * 
 * Centralized service for managing permission dependencies and relationships.
 * 
 * Features:
 * - Auto-cascading dependent permissions
 * - Permission completeness validation
 * - Reverse dependency tracking
 * - Permission label mapping for UI
 * - Audit logging for permission changes
 * 
 * @package App\Services
 */
class PermissionDependencyService
{
    /**
     * Cached dependency tree to avoid repeated config reads
     */
    private ?array $dependencyTreeCache = null;

    /**
     * Cached label map to avoid repeated config reads
     */
    private ?array $labelMapCache = null;

    /**
     * Get all direct dependencies for a permission
     * 
     * @param string $permissionName
     * @return array
     */
    public function getDependencies(string $permissionName): array
    {
        $dependencyTree = $this->getDependencyTree();
        return $dependencyTree[$permissionName] ?? [];
    }
    
    /**
     * Get all permissions that depend on a given permission (reverse dependencies)
     * Useful for cascading permission revocations
     * 
     * @param string $permissionName
     * @return array
     */
    public function getReverseDependencies(string $permissionName): array
    {
        $dependencyTree = $this->getDependencyTree();
        $reverseDeps = [];
        
        foreach ($dependencyTree as $parent => $children) {
            if (in_array($permissionName, $children)) {
                $reverseDeps[] = $parent;
            }
        }
        
        return $reverseDeps;
    }
    
    /**
     * Recursively get all dependent permissions (full dependency tree)
     * Prevents circular dependencies through visited tracking
     * 
     * @param string $permissionName
     * @param array $visited
     * @return array
     */
    public function getAllDependencies(string $permissionName, array $visited = []): array
    {
        if (in_array($permissionName, $visited)) {
            return []; // Circular dependency protection
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
     * Grant permission to role with auto-cascading dependencies
     * Automatically grants all required dependencies
     * 
     * @param Role $role
     * @param string $permissionName
     * @return array ['granted' => [], 'already_had' => [], 'failed' => []]
     */
    public function grantPermissionWithDependencies(Role $role, string $permissionName): array
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
                    Log::warning("Permission '{$perm}' not found for role '{$role->name}'");
                    continue;
                }
                
                if ($role->hasPermissionTo($permission)) {
                    $result['already_had'][] = $perm;
                } else {
                    $role->givePermissionTo($permission);
                    $result['granted'][] = $perm;
                    Log::info("Granted '{$perm}' to role '{$role->name}'");
                }
            } catch (\Exception $e) {
                $result['failed'][] = $perm;
                Log::error("Error granting '{$perm}' to role '{$role->name}': {$e->getMessage()}");
            }
        }
        
        return $result;
    }
    
    /**
     * Revoke permission from role with cascading dependents
     * Also revokes permissions that depend on this one
     * 
     * @param Role $role
     * @param string $permissionName
     * @return array ['revoked' => [], 'dependent_revoked' => [], 'failed' => []]
     */
    public function revokePermissionWithDependencies(Role $role, string $permissionName): array
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
                    Log::info("Revoked dependent '{$dependent}' from role '{$role->name}'");
                }
            } catch (\Exception $e) {
                $result['failed'][] = $dependent;
                Log::error("Error revoking dependent '{$dependent}': {$e->getMessage()}");
            }
        }
        
        // Then revoke the main permission
        try {
            $permission = Permission::where('name', $permissionName)->first();
            
            if ($permission) {
                $role->revokePermissionTo($permission);
                $result['revoked'][] = $permissionName;
                Log::info("Revoked '{$permissionName}' from role '{$role->name}'");
            }
        } catch (\Exception $e) {
            $result['failed'][] = $permissionName;
            Log::error("Error revoking '{$permissionName}': {$e->getMessage()}");
        }
        
        return $result;
    }
    
    /**
     * Validate permission completeness
     * Check if a role has all required dependencies for its granted permissions
     * 
     * @param Role $role
     * @return array ['complete' => [], 'incomplete' => [permission => [missing_deps]]]
     */
    public function validatePermissionCompleteness(Role $role): array
    {
        $rolePermissions = $role->permissions->pluck('name')->toArray();
        $dependencyTree = $this->getDependencyTree();
        
        $result = [
            'complete' => [],
            'incomplete' => []
        ];
        
        foreach ($rolePermissions as $permission) {
            $dependencies = $dependencyTree[$permission] ?? [];
            
            if (empty($dependencies)) {
                $result['complete'][] = $permission;
                continue;
            }
            
            $missingDeps = array_diff($dependencies, $rolePermissions);
            
            if (empty($missingDeps)) {
                $result['complete'][] = $permission;
            } else {
                $result['incomplete'][$permission] = $missingDeps;
            }
        }
        
        return $result;
    }
    
    /**
     * Auto-fix incomplete permissions
     * Automatically grant missing dependencies to make role permissions complete
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
                        Log::info("Auto-fixed missing '{$missingDep}' for role '{$role->name}' (required by '{$permission}')");
                    }
                } catch (\Exception $e) {
                    $result['failed'][] = $missingDep;
                    Log::error("Error fixing '{$missingDep}': {$e->getMessage()}");
                }
            }
        }
        
        return $result;
    }
    
    // ==================== UI Helper Methods ====================
    
    /**
     * Get permission dependency tree (for UI display)
     * Uses caching to avoid repeated config reads
     * 
     * @return array ['permission.name' => ['dep1', 'dep2'], ...]
     */
    public function getDependencyTree(): array
    {
        if ($this->dependencyTreeCache === null) {
            $this->dependencyTreeCache = $this->buildDependencyTree();
        }
        
        return $this->dependencyTreeCache;
    }
    
    /**
     * Build permission label map
     * Creates a flat map of permission names to their human-readable labels
     * Uses caching for performance
     * 
     * @return array ['permission.name' => 'Human Label', ...]
     */
    public function buildPermissionLabelMap(): array
    {
        if ($this->labelMapCache === null) {
            $this->labelMapCache = $this->buildLabelMap();
        }
        
        return $this->labelMapCache;
    }
    
    /**
     * Get label for a single permission
     * 
     * @param string $permissionName
     * @return string
     */
    public function getPermissionLabel(string $permissionName): string
    {
        $labelMap = $this->buildPermissionLabelMap();
        return $labelMap[$permissionName] ?? $permissionName;
    }
    
    /**
     * Get permissions with enhanced metadata for UI
     * Adds label map and dependency info for view rendering
     * 
     * @return array
     */
    public function getPermissionsWithMetadata(): array
    {
        return [
            'permissions' => config('permission.permissions', []),
            'labelMap' => $this->buildPermissionLabelMap(),
            'dependencyTree' => $this->getDependencyTree()
        ];
    }
    
    /**
     * Generate audit log entry for permission changes
     * 
     * @param Role $role
     * @param string $action ('grant'|'revoke'|'create'|'update')
     * @param array $permissions
     * @param array $auditData
     * @return void
     */
    public function auditPermissionChange(Role $role, string $action, array $permissions, array $auditData = []): void
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
    
    // ==================== Private Helper Methods ====================
    
    /**
     * Build dependency tree from permissions config
     * Extracts 'dependencies' arrays from permissions that have them
     * 
     * @return array ['permission.name' => ['dep1', 'dep2'], ...]
     */
    private function buildDependencyTree(): array
    {
        $permissionsConfig = config('permission.permissions', []);
        $dependencyTree = [];
        
        foreach ($permissionsConfig as $group => $permissions) {
            foreach ($permissions as $permName => $permData) {
                if (is_array($permData) && isset($permData['dependencies'])) {
                    $dependencyTree[$permName] = $permData['dependencies'];
                }
            }
        }
        
        return $dependencyTree;
    }
    
    /**
     * Build label map from permissions config
     * 
     * @return array
     */
    private function buildLabelMap(): array
    {
        $permissionsConfig = config('permission.permissions', []);
        $labelMap = [];
        
        foreach ($permissionsConfig as $group => $permissions) {
            foreach ($permissions as $permName => $permData) {
                // Extract label: either from array['label'] or direct string value
                $label = is_array($permData) ? ($permData['label'] ?? $permName) : $permData;
                $labelMap[$permName] = $label;
            }
        }
        
        return $labelMap;
    }
    
    /**
     * Clear internal caches (useful for testing or dynamic config changes)
     * 
     * @return void
     */
    public function clearCache(): void
    {
        $this->dependencyTreeCache = null;
        $this->labelMapCache = null;
    }
}
