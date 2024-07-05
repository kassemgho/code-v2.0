<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Student;
use App\Models\StudentSubject;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CategoryStudent>
 */
class CategoryStudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_id' =>1,
            'student_id' => 1,
        ];
    }
}
