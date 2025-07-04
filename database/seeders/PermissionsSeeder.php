<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\User;



class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
        DB::table('role_has_permissions')->truncate();
        DB::table('model_has_permissions')->truncate();
        DB::table('model_has_roles')->truncate();
        DB::table('permissions')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');


        $devUser        = User::find(1);
        $adminUser      = User::find(2);
        // $employeeUser   = User::find(3);

        $developerRole  = Role::find(1);
        $adminRole      = Role::find(2);
        // $employeeRole   = Role::find(3);


        $ignored = config('permission.ignore_routes', []);
        $default_permissions = config('DefaultPermissions') ?? [];
        
        $Permissions = collect(Route::getRoutes())
            ->filter(fn ($route) => !is_null($route->getName()) && !in_array($route->getName(), $ignored))
            ->map(fn ($route) => $route->getName())
            ->toArray();

        $mergedPermissions = array_unique(array_merge($default_permissions, $Permissions));

        // Insert or ignore permissions (to avoid duplicates)
        foreach ($mergedPermissions as $permission) {
            Permission::updateOrCreate(['name' => $permission], ['guard_name' => 'web']);
        }

        $developerRole->syncPermissions(Permission::all());
        $adminRole->syncPermissions(Permission::all());
        // $employeeRole->syncPermissions(Permission::all());

        $devUser->syncRoles($developerRole->id);
        $adminUser->syncRoles($adminRole->id);
        // $employeeUser->syncRoles($employeeRole->id);

        $this->command->info('Permissions seeded and synced to roles.');
    }
}
