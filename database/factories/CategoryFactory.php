<?php

namespace Database\Factories;

use App\Models\Subject;
use App\Models\SubjectTeacher;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
        'name' => 'programing1_1',
            'mark_of_commings' => 5,
            'mark_of_ratings' => 10,
            'subject_id' => 1,
            'teacher_id' => 1 ,
        ];
    }
}
