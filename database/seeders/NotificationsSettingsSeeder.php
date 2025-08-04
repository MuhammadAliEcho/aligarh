<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class NotificationsSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $settings = [
            'student_attendance',
            'teacher_attendance',
            'employee_attendance',
            'send_msg',
        ];

        DB::table('notifications_settings')
            ->whereNotIn('name', $settings)
            ->delete();

        $now = Carbon::now();

        foreach ($settings as $name) {
            $exists = DB::table('notifications_settings')
                ->where('name', $name)
                ->exists();

            if (! $exists) {
                DB::table('notifications_settings')->insert([
                    'name' => $name,
                    'mail' => 0,
                    'sms' => 0,
                    'whatsapp' => 0,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }
}
