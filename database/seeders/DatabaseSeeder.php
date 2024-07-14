<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Teacher;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            SubjectSeeder::class,
            StudentSeeder::class,
            TeacherSeeder::class ,
        ]);
        \App\Models\User::create([
            'name' => 'super admin' ,
            'email_verified_at' => now() ,
            'email' => 'superAdmin@gmail.com',
            'password' => Hash::make('passwordSuper'),
            'remember_token' => Str::random(10),
            'role' => 'adminstrator'
        ]);
        \App\Models\User::create([
            'name' => 'monitor' ,
            'email_verified_at' => now() ,
            'email' => 'monitor@gmail.com',
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'role' => 'monitor'
        ]);
        \App\Models\Category::factory()->create();
        \App\Models\Teacher::factory(10)->create();
        \App\Models\Administrator::factory(1)->create();
        \App\Models\Tag::factory(10)->create();
        \App\Models\Problem::factory(10)->create();
        \App\Models\ProblemTag::factory(10)->create();
        // \App\Models\SolveProblem::factory(10)->create();
        \App\Models\TestCase::factory(10)->create();
        \App\Models\Subject::factory(5)->create();
        \App\Models\Exam::factory()->create();
        $this->call([
            TrueFalseQustionSeeder::class
        ]);
        \App\Models\CategoryStudent::factory()->create();
        \App\Models\ExamStudent::factory(1)->create();
        // \App\Models\TrueFalseQuestion::factory(10)->create();
        // \App\Models\Answer::factory(1)->create();
        \App\Models\Contest::factory(1)->create([
            'start_at' => now()->format('y-m-d'),
            'contest_time' => now()->format('H:i:s')
        ]);
        \App\Models\Contest::factory(9)->create();
        \App\Models\ContestProblem::factory(5)->create();
        \App\Models\ContestStudent::factory(1)->create();
        \App\Models\Assessment::factory(1)->create(
            [
            'category_id'=> 1,
            'teacher_id'=>1 ,
            'problem_id'=>1,
            ]
        );
        \App\Models\Assessment::factory(10)->create();
        \App\Models\AssessmentStudent::factory(1)->create();

        
    }
}
