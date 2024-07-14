<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\ExamStudent;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class YearlyDataCleanup extends Command
{
    protected $signature = 'yearly:cleanup';
    protected $description = 'Backup and clean up yearly data';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info('Starting yearly data cleanup...');

        // Step 1: Backup the database
        $this->backupDatabase();

        // Step 2: Clean up the data
        $this->cleanupData();

        $this->info('Yearly data cleanup completed successfully!');
    }

    private function backupDatabase()
    {
        $dbHost = env('DB_HOST');
        $dbName = env('DB_DATABASE');
        $dbUser = env('DB_USERNAME');
        $dbPass = env('DB_PASSWORD');
        $dbPort = env('DB_PORT');
        $backupPath = storage_path('app/public/backups/' . date('Y-m-d_H-i-s') . '_backup.sql');
        $path = public_path('test.sql') ;
        $command = "mysqldump --host=$dbHost --user=$dbUser --port=$dbPort --password=$dbPass $dbName > $backupPath";

        system($command, $output);

        if ($output === 0) {
            $this->info("Backup successful: $backupPath");
        } else {
            $this->error('Backup failed' );
        }
    }

    private function cleanupData()
    {   
        $categories = Category::all();
        foreach($categories as $category){
            $students = $category->students()->with('user')->get();
            $exam = $category->subject->exam ;
            $assessments = $category->assessments()->get() ;
            foreach($students as $student){
                    
                $studnet_assessments = $student->assessments->where('category_id' , $category->id);
                // return $studnet_assessments ;
                $student['attendance_mark'] = 
                ($assessments->count() != 0)?round(($studnet_assessments->count() / $assessments->count() ) * $category->mark_of_commings):0 ;
                $assessment_mark =  0 ;
                foreach($studnet_assessments as $assessment){
                    $assessment_mark+= $assessment->pivot->mark ;
                }
                
                $student['assessment_mark'] =
                ($assessments->count() != 0)? round(($assessment_mark / ($assessments->count()*100)) * $category->mark_of_ratings) :0;
                $exam_mark = ExamStudent::where('exam_id' , $exam->id)->where('student_id' , $student->id)->first();
                $student['exam_mark'] = round(($exam_mark != NULL)  ? $exam_mark->mark : 0) ;
                $student['total'] = $student['attendance_mark'] + $student['assessment_mark']  +$student['exam_mark']  ;
                $subject = $category->subject()->get() ;
                $subject->students()->updateExistingPivot($student['id'],['final_mark'=>$student['total']]);
            }
        }
        // Example queries to clean up data
        DB::table('assessment_student')->delete();
        DB::table('category_student')->delete();
        DB::table('assessments')->delete();
        DB::table('categories')->delete();
        DB::table('exam_student')->delete();
        DB::table('exams')->delete();
        // Add additional tables as needed

        $this->info('Data cleanup completed.');
    }
}
