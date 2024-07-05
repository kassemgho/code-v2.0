<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Subject::create(['name' => 'programin1']);
        Subject::create(['name' => 'programin2']);
        Subject::create(['name' => 'algorithm1']);
        Subject::create(['name' => 'programin3']);
        Subject::create(['name' => 'programin2']);
    }
}
