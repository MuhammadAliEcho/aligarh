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
        $employeeRole   = Role::find(3);
        $teacherRole    = Role::find(4);


        $employeePermissions = [
            'dashboard',
            'dashboard.timeline',
            'user-settings.index',
            'user-settings.password.update',
            'vendors.index',
            'vendors.add',
            'vendors.edit',
            'vendors.edit.post',
            'items.index',
            'items.add' ,
            'items.edit',
            'items.edit.post',
            'vouchers.index',
            'vouchers.add',
            'vouchers.edit',
            'vouchers.edit.post',
            'vouchers.detail',
            'expense.index',
            'expense.add',
            'expense.edit',
            'expense.edit.post',
            'expense.summary',
        ];

        $teacherPermissions = [
            'dashboard',
            'dashboard.timeline',
            'user-settings.password.update',
            'routines.index',
            'routines.add',
            'routines.edit',
            'routines.edit.post',
            'routines.delete',
            'manage-subjects.index',
            'manage-subjects.add',
            'manage-subjects.edit',
            'manage-subjects.edit.post',
            'exam.index',
            'exam.add',
            'exam.edit',
            'exam.edit.post',
            'manage-result.index',
            'manage-result.make',
            'manage-result.attributes',
            'manage-result.maketranscript',
            'manage-result.maketranscript.create',
            'manage-result.result',
            'library.index',
            'library.add',
            'library.edit',
            'library.edit.post',
            'exam-grades.index',
            'exam-grades.update',
        ];


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

        //Sync roles
        $developerRole->syncPermissions(Permission::all());
        $adminRole->syncPermissions(Permission::all());
        $employeeRole->syncPermissions($employeePermissions);
        $teacherRole->syncPermissions($teacherPermissions);

        //Sync users
        $devUser->syncRoles($developerRole->id);
        $adminUser->syncRoles($adminRole->id);
        // $employeeUser->syncRoles($employeeRole->id);

        $this->command->info('Permissions seeded and synced to roles.');
    }
}
