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
            'student_attendance' => 'This is to inform you that your child, {name}, was marked present today. Please ensure they continue attending regularly and punctually.',
            'teacher_attendance' => "Dear {name},\nThank you for attending school today. Your presence and dedication are valued and appreciated.\nBest regards,\nSchool Administration",
            'employee_attendance' => "Dear {name},\nThank you for being present at work today. Your commitment and punctuality are appreciated.\nBest regards,Hr School Team",
            'send_msg' => '',
        ];

        // Delete any settings not in the list
        DB::table('notifications_settings')
            ->whereNotIn('name', array_keys($settings))
            ->delete();

        $now = Carbon::now();

        foreach ($settings as $name => $message) {
            $exists = DB::table('notifications_settings')
                ->where('name', $name)
                ->exists();

            if (! $exists) {
                DB::table('notifications_settings')->insert([
                    'name' => $name,
                    'message' => $message,
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
