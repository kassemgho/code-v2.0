<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamStudent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Expr\FuncCall;

class ExamController extends Controller
{
    public $student ;
    public function __construct() {
        $this->student = Auth::user()->student;
    }
    public function showExam(Exam $exam){
        if (!$exam->students()->where('student_id', $this->student->id)->exists()) {
            return 'can not access this exam' ;
        }
        if ($exam->time > now()){
            abort(403,'exam not started yet');
        }
        if ($exam->time < now()->subMinutes($exam->duration)){
            abort(403,'exam over');
        }
        
        $exam->trueFalseQuestions ;
        $exam->problem1 ;
        return ['exam' => $exam] ;
    }
    public function index(){
        $exams = $this->student->exams()->where('time' < now())->get();
        return $exams ;
    }
    public function solve(Exam $exam , Request $request){
        if (!$exam->students->contains($this->student->id)){
            return ['message' => 'you can not solve this exam'];
        }
        if ($exam->students()->where('student_id' , $this->student->id)->first()->pivot->mark != 0){
            return ['message'=> 'you already solve this exam'];
        }
         if ($exam->time > now()){
            abort(403,'exam not started yet');
        }
        if ($exam->time < now()->subMinutes($exam->duration)){
            abort(403,'exam over');
        }
        
        $request->validate([
            'anseres' => 'required|array',
            'problem_mark' => 'required|integer|max:15|min:0' 
        ]);
        $count = 0;
        $qustions = $exam->trueFalseQuestions()->get();
        $examStudent = ExamStudent::where('exam_id' , $exam->id)
        ->where('student_id' , $this->student->id)
        ->get();
        foreach($request->anseres as $answere){
            $examStudent->answers->create([
                'true_false_question_id' => $answere['true_false_question_id'],
                'correct' => $answere['correct']
            ]);
            foreach($qustions as $qustion){
                if ($qustion->id == $answere['true_false_question_id'] 
                && $answere['answere'] == $qustion->correct){
                    $count++;
                }
            }
        }
        $examMark = ($count / count($qustions) ) * 10 + $request->problem_mark ;
        $examStudent->mark = $examMark ;
        $examStudent->save();
        return [
            'message' => "your mark is $examMark , amd you can review your solve in any time "
        ];
    }
    public function submit(Exam $exam , Request $request)
    {
        if ($exam->students()->where('student_id' , $this->student->id)->first()->pivot->mark != 0){
            return ['message'=> 'you already solve this exam'];
        }
        $request->validate([
            'code' => 'required',
            'lang' => 'required'
        ]);
        $precent = ProblemController::solveProlem($exam->problem1->id , $request)['percent'];
        $markOfProblem = 5 ;
        $exam->students()->updateExistingPivot($this->student->id , ['mark' => $precent*$markOfProblem]);  
        return ['message' => "you get $precent*$markOfProblem and you can resubmit the solve \n but note , once you subimt you will lose the old solve and mark "];
    }
    public function showSolve(Exam $exam){
        $solve = ExamStudent::where('exam_id' , $exam->id)
        ->where('student_id' , $this->student->id)->first();
        $solve->answers ;
        $exam->problem1 ; 
        $exam->trueFalseQuestions;
        return [
            'exam' => $exam ,
            'solve' => $solve
        ];
    }
   
}
