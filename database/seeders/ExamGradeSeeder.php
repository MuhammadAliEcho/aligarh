<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class ExamGradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        DB::table('grades')->insert([
            [
                'id' => 1,
                'from_percent' => 79.9,
                'to_percent' => 100,
                'prifix' => 'A+',
                'name' => 'A+',
            ],
            [
                'id' => 2,
                'from_percent' => 69.9,
                'to_percent' => 79.9,
                'prifix' => 'A',
                'name' => 'A',
            ],
            [
                'id' => 3,
                'from_percent' => 59.9,
                'to_percent' => 69.9,
                'prifix' => 'B',
                'name' => 'B',
            ],
            [
                'id' => 4,
                'from_percent' => 49.9,
                'to_percent' => 59.9,
                'prifix' => 'C',
                'name' => 'C',
            ],
            [
                'id' => 5,
                'from_percent' => 39.9,
                'to_percent' => 49.9,
                'prifix' => 'D',
                'name' => 'D',
            ],
            [
                'id' => 6,
                'from_percent' => 20,
                'to_percent' => 39.9,
                'prifix' => 'F',
                'name' => 'F',
            ],
        ]);
    }
}