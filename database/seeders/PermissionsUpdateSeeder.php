<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PermissionsUpdateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Log::info('PermissionsUpdateSeeder: Starting permission sync.');

        $ignored = config('permission.ignore_routes', []);
        $default_permissions = config('DefaultPermissions') ?? [];

        // Get all route names, excluding ignored ones
        $routePermissions = collect(Route::getRoutes())
            ->filter(fn($route) => !is_null($route->getName()) && !in_array($route->getName(), $ignored))
            ->map(fn($route) => $route->getName())
            ->toArray();

        // Merge with default permissions
        $mergedPermissions = array_unique(array_merge($default_permissions, $routePermissions));

        if (count($mergedPermissions) > 500) {
            Log::warning('PermissionsUpdateSeeder: More than 500 permissions detected. Optimizing insert operations.');
        }

        // Sync permission records: insert or update
        foreach (array_chunk($mergedPermissions, 500) as $chunk) {
            foreach ($chunk as $permissionName) {
                Permission::updateOrCreate(
                    ['name' => $permissionName],
                    ['guard_name' => 'web']
                );
            }
        }

        // Remove permissions not in the merged list
        $existingPermissionNames = Permission::pluck('name')->toArray();
        $permissionsToDelete = array_diff($existingPermissionNames, $mergedPermissions);

        if (!empty($permissionsToDelete)) {
            $permissionsToDeleteIds = Permission::whereIn('name', $permissionsToDelete)->pluck('id')->toArray();

            // Remove from role_has_permissions table
            DB::table('role_has_permissions')
                ->whereIn('permission_id', $permissionsToDeleteIds)
                ->delete();

            // Delete from permissions table
            Permission::whereIn('id', $permissionsToDeleteIds)->delete();

            Log::info("PermissionsUpdateSeeder: Deleted " . count($permissionsToDelete) . " obsolete permissions.");
        }

        // Sync permissions to developer role (Role ID = 1)
        $developerRole = Role::find(1);
        if ($developerRole) {
            $developerRole->syncPermissions(Permission::all());
            Log::info("PermissionsUpdateSeeder: Synced all permissions to Developer role (ID 1).");
        } else {
            Log::warning("PermissionsUpdateSeeder: Developer role (ID 1) not found. Skipping role sync.");
        }

        Log::info('PermissionsUpdateSeeder: Permission sync completed.');
    }
}
