<?php

namespace Database\Seeders;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => 'teacher' ,
            'email' => 'teacher@gmail.com' ,
            'password' => 'password' ,
            'role' => 'teacher',
        ]);
        $user->teacher()->create([
            'phone_number' => '0998085197'
        ]);
    }
}
