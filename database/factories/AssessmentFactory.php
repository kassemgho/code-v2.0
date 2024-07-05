<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Problem;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Assessment>
 */
class AssessmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $Category = Category::inRandomOrder()->first() ;
        return [
            'name' => 'assessment' . rand(0,10),
            'category_id'=> 1,
            'teacher_id'=>1 ,
            'problem_id'=>1,
        ];
    }
}
