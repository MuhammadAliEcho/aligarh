<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserSeeder::class);
        $this->call(PermissionsSeeder::class); //for Reset all permission role, etc 
        $this->call(NotificationsSettingsSeeder::class); 
        // $this->call(PermissionsUpdateSeeder::class); // only delete permissions and  Sync roles
    }
}
