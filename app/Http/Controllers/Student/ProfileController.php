<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ExamStudent;
use App\Models\StudentSubject;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ProfileController extends Controller
{
    public function show() {
        $user = auth()->user();
        $student = $user->student()->first();
        $qrcode = QrCode::generate($student->university_id);
        $student['materials'] = $student->categories()
            ->get()
            ->map(function ($category) use ($student){
            
            $student_subject = StudentSubject::where('student_id' , $student->id)
                ->where('subject_id', $category->first()['subject_id'])
                ->first();
            if ($student_subject)
            $mark =  StudentSubject::where('student_id' , $student->id)
                ->where('subject_id', $category->first()['subject_id'])
                ->first()['final_mark'];
            $mark = 0;
            if ($mark > 0) {
                $student['total'] = $mark;
            }else {
                $studnet_assessments = $student->assessments()->where('category_id' , $category->id)->get();
                // return $studnet_assessments ;
                $assessments = $category->assessments()->get();
                // $student['attendance_mark'] = (($studnet_assessments->count() / $assessments->count() ) * $category->mark_of_commings) ;
                $assessment_mark =  0 ;
                
                foreach($studnet_assessments as $assessment){
                    $assessment_mark+= $assessment->pivot->mark ;
                }
                
                if ($assessments->count() > 0) {
                    $student['attendance_mark'] = (($studnet_assessments->count() / $assessments->count()) * $category->mark_of_commings);
                    $student['assessment_mark'] = (($assessment_mark / ($assessments->count() * 100)) * $category->mark_of_ratings);
                }else {
                    $student['attendance_mark'] = 0;
                    $student['assessment_mark'] = 0;
                }
                $exam_mark = NULL;
                // $student['assessment_mark'] = (($assessment_mark / ($assessments->count()*100)) * $category->mark_of_ratings) ;
                $exam = $category->subject->exam;
                if ($exam != NULL)
                    $exam_mark = ExamStudent::where('exam_id' , $exam->id)->where('student_id' , $student->id)->first();
                $student['exam_mark'] = (($exam_mark != NULL)  ? $exam_mark->mark : 0) ;
                $student['total'] = $student['attendance_mark'] + $student['assessment_mark']  +$student['exam_mark']  ;
            }
        
                return [
                    'subject_name' => $category->subject->name,
                    'degree' => round($student['total']),
                    'date' => Carbon::parse($category->created_at)->format('Y-m-d'),
                ];
            });
            $student['solutions'] = $student->hard + $student->medium + $student->easy;
            $student['detail'] = $user;
            $student['image'] = base64_encode($qrcode);
        
        return $student;
    }
      public function update(Request $request){
        $user = auth()->user() ;
        $student = $user->student ;
        $request = $request->except(['id','university_id','role','password']);
        
        $student->update($request) ;
        $user->update($request);
        $student->user ;
        return response()->json([
            'data' => $student , 
            'message' => 'updated successfully'
        ]);
    }
    public function changePassword(Request $request){
        $request->validate([
            'old_password' => 'required|string',
            'new_password' => 'required|string' ,
        ]);
        $user = auth()->user();
        
        // Verify the old password
        if (!Hash::check($request->old_password, $user->password)) {
            return ['message' => 'The old password is incorrect'];
        }

        // Update the password
        $user->update([
            'password' => Hash::make($request->new_password),
        ]);
        return [
            'message' => 'password updated successfully',
            'user' => $user->email     
        ] ; 
    }
    
    
}
