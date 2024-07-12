<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public static function run(): void
    {
        $students = [
            [
                'name' => 'Kassem Ghotani',
                'user_id' => 2,
                'university_id' => 12345
            ],
            [
                'name' => 'Mouaz Abbas',
                'user_id' => 1,
                'university_id' => 99999  
            ],
            [
                'name' => 'Abd Fayad',
                'user_id' => 3 ,
                'university_id' => 1854796
            ],
            [
                'name' => 'Noha Salem',
                'user_id' => 4,
                'university_id' => 1855797
            ],
            [
                'name' => 'Loujain Ibrah',
                'user_id' => 5,
                'university_id' => 1865478
            ],
            [
                'name' => 'Mohamed Taha',
                'user_id' => 6,
                'university_id' => 112211
            ],
            [
                'name' => 'Seba Talea',
                'user_id' => 7,
                'university_id' => 22255
            ],
            [
                'name' => 'Lilian Kabool',
                'user_id' => 8,
                'university_id' => 1122111
            ],
            [
                'name' => 'Lana Essa',
                'user_id' => 9,
                'university_id' => 11212121
            ],
        
        ];
        collect($students)->map(function($stu){ 
            $user = User::factory()->create(['name' => $stu['name'] ,'role' => 'student' ,'email' => $stu['university_id'] ] );
            $user->student()->create([
                'university_id' => $stu['university_id'],
            ]);
        });
        // $user = User::create([
        //     'email' => '12345',
        //     'password' => 'password' ,
        //     'name' => 'kassem ghotani' ,
        //     'role' => 'student'

        // ]);
        // $user->student()->create([
        //     'phone_number' => '0998085197',
        //     'university_id' => 12345
        // ]);
          
        
    }
}
