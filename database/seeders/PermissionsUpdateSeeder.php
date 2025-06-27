<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Route;


class PermissionsUpdateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ignored = config('permission.ignore_routes', []);

        $Permissions = collect(Route::getRoutes())
            ->filter(fn ($route) => !is_null($route->getName()) && !in_array($route->getName(), $ignored))
            ->map(fn ($route) => $route->getName());



        // Insert or ignore permissions (to avoid duplicates)
        foreach ($Permissions as $permission) {
            Permission::updateOrCreate(['name' => $permission], ['guard_name' => 'web']);
        }

        // Sync roles
        Role::find(1)?->syncPermissions(Permission::all()); // Developer
        Role::find(2)?->syncPermissions(Permission::all()); // Admin
        // Role::find(3)?->syncPermissions(Permission::all()); // Employee

        $this->command->info('Permissions update seeded and synced to roles.');
        
    }
}
