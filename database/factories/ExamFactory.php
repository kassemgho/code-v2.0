<?php

namespace Database\Factories;

use App\Models\Administrator;
use App\Models\Problem;
use App\Models\Subject;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Exam>
 */
class ExamFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'passwd' => 'password',
            'administrator_id' => Administrator::inRandomOrder()->value('id'),
            'name' => 'programing 1',
            'time' => now(),
            'subject_id' => 1,
            'problem1_id' => 1,
        ];
    }
}
