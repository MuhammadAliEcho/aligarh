<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
        DB::table('users')->truncate();
        DB::table('roles')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');

        DB::table('users')->insert([
            [
                'name' => 'developer',
                'email' => 'dev@dev.com',
                'password' => Hash::make('12345678'),
                'user_type' => 'employee',
                'settings' => '{"skin_config":{"nav_collapse":""}}',
                'created_by' => 1,
            ],
            [
                'name' => 'admin',
                'email' => 'admin@admin.com',
                'password' => Hash::make('12345678'),
                'user_type' => 'employee',
                'settings' => '{"skin_config":{"nav_collapse":""}}',
                'created_by' => 1,
            ]
        ]);

        DB::table('roles')->insert([
            [
                'name' => 'Developer',
                'guard_name' => 'web',
                'created_by' =>  1,
                'created_at' =>  Carbon::now(),
            ],
            [
                'name' => 'Admin',
                'guard_name' => 'web',
                'created_by' =>  1,
                'created_at' =>  Carbon::now(),
            ],
            [
                'name' => 'Employee',
                'guard_name' => 'web',
                'created_by' =>  1,
                'created_at' =>  Carbon::now(),
            ],
            [
                'name' => 'Teacher',
                'guard_name' => 'web',
                'created_by' =>  1,
                'created_at' =>  Carbon::now(),
            ],
        ]);
    }
}
