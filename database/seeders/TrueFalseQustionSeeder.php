<?php

namespace Database\Seeders;

use App\Models\TrueFalseQuestion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TrueFalseQustionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 'exam_id', 'question_text', 'choise1', 'choise2', 'choise3', 'choise4', 'correct'
         $questions = [
            [
                'question_text' => 'what is variable name is correct in cpp',
                'choise1' => '$test',
                'choise2' => '#test',
                'choise3' => '_test',
                'choise4' => '@test',
                'correct' => 3 ,
                'code' => null ,  
            ],
            [
                'question_text' => 'what is output of this code ',
                'choise1' => '0',
                'choise2' => '100',
                'choise3' => '1',
                'choise4' => '33.33333',
                'correct' => 3 ,
                'code' => ' #include<iostram> 
                            using namespace std ; 
                            int main(){
                                int x = 100 ; 
                                cout << x % 3 ;
                                return 0 ;
                            }' ,  
            ],
            [
                'question_text' => 'in c++ , if we have tow functions with the same name , but with different parameters ',
                'choise1' => 'we get sentax error ',
                'choise2' => 'we get run time error ',
                'choise3' => 'if the was in the same scope then error ',
                'choise4' => 'not in above',
                'correct' => 4 ,
                'code' => null ,  
            ],
            [
                'question_text' => 'how we can fix the run time error ( divide by zero ) in this code ',
                'choise1' => 'there is no run time error ',
                'choise2' => 'add if statment to check if y equal to zero ',
                'choise3' => 'calculate the result in a diifernt variable , and print the variable ',
                'choise4' => 'not in above',
                'correct' => 3 ,
                'code' => ' #include<iostram> 
                            using namespace std ; 
                            int main(){
                                int x , y ;
                                cin >> x >> y ; 
                                cout << x / y;
                                return 0 ;
                            }' ,  
            ]
        ]; 

        collect($questions)->map(function($question){
            $question['exam_id'] = 1 ;
            TrueFalseQuestion::create($question);
        });
    }
}
