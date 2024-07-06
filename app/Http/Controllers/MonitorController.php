<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MonitorController extends Controller
{
    public function getCurrentExam(){
        $exam = Exam::where('time', '<', now())
                ->whereRaw('time >= ? - INTERVAL duration MINUTE', [now()])
                ->first();
        return $exam != null ? ['exam'=>$exam] : response()->json(['message' => 'there is no exam now '. now()] , 300);
    }
    public function checkStudent( Exam $exam , $id){
        $student = Student::where('university_id' , $id)->first() ;
        if ($student == null){
            return response()->json([
                'messaage' => 'there is no student with this university_id in the database '
            ],400);
        }
        if ( !$exam->students->contains($student->id) ){
            return response()->json([
                'messaage' => 'there is no student with this university_id in the exam '
            ],400);
        }
        $exam->students()->updateExistingPivot($student->id , ['check'=>2]);
        return ['message' => 'done'] ;
    }
}
