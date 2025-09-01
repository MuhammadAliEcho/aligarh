<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DefaultClassSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();
        // 1. Seed Classes
        $classes = [
            [
                'name' => 'Primary One',
                'numeric_name' => 1,
                'prifix' => 'P1',
                'teacher_id' => null,
                'created_by' => 1,
                'updated_by' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Primary Two',
                'numeric_name' => 2,
                'prifix' => 'P2',
                'teacher_id' => null,
                'created_by' => 1,
                'updated_by' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::table('classes')->insert($classes);

        // Get inserted class IDs
        $class1Id = DB::table('classes')->where('numeric_name', 1)->value('id');
        $class2Id = DB::table('classes')->where('numeric_name', 2)->value('id');

        // 2. Seed Sections for each Class
        $sections = [
            [
                'name' => 'Section A',
                'nick_name' => 'A',
                'class_id' => $class1Id,
                'teacher_id' => null,
                'capacity' => 40,
                'created_by' => 1,
                'updated_by' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Section B',
                'nick_name' => 'B',
                'class_id' => $class1Id,
                'teacher_id' => null,
                'capacity' => 40,
                'created_by' => 1,
                'updated_by' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Section A',
                'nick_name' => 'A',
                'class_id' => $class2Id,
                'teacher_id' => null,
                'capacity' => 40,
                'created_by' => 1,
                'updated_by' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::table('sections')->insert($sections);
    }
}
