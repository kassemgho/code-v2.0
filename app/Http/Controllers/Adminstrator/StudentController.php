<?php

namespace App\Http\Controllers\Adminstrator;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ChangeCategoryRequest;
use App\Models\SetOfStudent;
use App\Models\Student;
use App\Models\Subject;
use App\Models\User;
use Egulias\EmailValidator\Result\Reason\Reason;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use Maatwebsite\Excel\Facades\Excel;

class StudentController extends Controller
{
    public function index() {
        $data['students'] = Student::with('user')->get()->map(function ($student) {
            return [
                'id' => $student->id,
                'student_name' => $student->user->name,
                'student_email' => $student->user->email,
                'phone_number' => $student->phone_number,
                "hint_count" => $student->hint_count,
                "points" => $student->points,
                "rate" => $student->rate,
                "date_of_birth" => $student->date_of_birth,
                "easy" => $student->easy,
                "medium" => $student->medium,
                "hard" => $student->hard,
                "university_id" => $student->university_id,
            ];
        });
        $data['change_class_request'] = ChangeCategoryRequest::all()->map(function ($request) {
            return [
                'id' => $request->id,
                'student_name' => $request->student->user->name,
                'old_class' => $request->old_category,
                'new_class' => $request->new_category,
                'reason' => $request->reason
            ];
        });
        return $data;
    }
    public function changeStudentPassword(Student $student) {
        $new_password = Str::random(16);
        $user = User::where('id', $student->user_id)->first();
        $user->password = Hash::make($new_password);
        $user->save();
        return response()->json([
            'message' => 'your password changed successfully',
            'new_password' => $new_password
        ]);
    }
    public function importStudents(Request $request ){
        $request->validate([
            'file' => 'required'
        ]);

        $file = $request->file('file');
        $rows = Excel::toCollection([] , $file)[0];
        DB::beginTransaction();
        $studentExists = [] ;
        $i=0;
        foreach($rows as $row){
            if ($row[0] == 'number')continue ;
            if (Student::where('university_id' , $row[0])->exists()){
                $studentExists[$i]['number'] = $row[0] ;
                $studentExists[$i]['name'] = $row[1];
                $i++;
            }else {
                // SetOfStudent::create([
                //     'number' =>$row[0] ,
                //     'name' =>  $row[1]
                // ]);
            
                $user = User::create([
                    'email' => $row[0] ,
                    'name' => $row[1],
                    'password' => ($row[2]) ,
                    'role' => 'student'
                ]);
                $user->student()->create([
                    'university_id' => $row[0],
                ]);
            }
        }
        DB::commit();
        if (count($studentExists)!=0){
            return response()->json([
                'message' => 'those students alrady exists' ,
                'students' => $studentExists 
            ],300);
        }
        return ['message' => 'studnts added successfully']; 
        
    }
        
    public function distributeCategories(Request $request){
        $request->validate([
            'classes' => 'required|integer',
            'year' => 'required|integer',
            'file' => 'required',
        ]);
        $subjects = ($request->year == 1) ? [1,2] : [3 ,4 ,5] ;
        $file = $request->file('file');
        $rows = Excel::toCollection([] , $file)[0];
        $result = $this->distribute($request , $subjects , $rows);
        if ($result != NULL){
        return response()->json([
            'message' =>  'added successfully' ,
            'students' => $result
        ],300);
        }
        return response()->json([
            'message' =>  'added successfully' ,
        ]);
    }
    private function distribute($request , $subjects , $rows){
        $categories = [];
         for ($i = 1; $i <= $request->classes * count($subjects); $i++) {
            $subject_id = $subjects[(int)(($i-1)/$request->classes)] ;
            $subject = Subject::where('id' , $subject_id)->first();
            
            $categories [] = Category::create([
                'subject_id' => $subject->id ,
                'name' => $subject->name.'_'. (($i-1) % count($subjects) + 1),
            ]);
        }
        DB::beginTransaction();
        foreach ($rows as $row){
            if ($row[0] == 'class') continue;
            $studentsNotExists = [] ;
            $student = Student::where('university_id', $row[1])->first();
            // $student = $user->student;
            if ($student == null){
                $studentsNotExists [] = ['name' => $row[2] , 'number' => $row[1]];
            }else 
            foreach($categories as $category){
                if ($category->name[strlen($category->name)-1] == $row[0]){
                    
                    if ($student->subjects->contains($subject->id)){
                        $student->subjects()->detach($subject->id);
                        $oldCategories = $subject->categories()->get();
                        foreach ($oldCategories as $oldCategory){
                            $student->categories()->detach($oldCategory->id);
                        }
                    }
                    
                    $student->categories()->attach($category->id);
                    $subject = $category->subject;
                    $student->subjects()->attach($subject->id);
                }
            }
        }
        DB::commit();
        return $studentsNotExists ;
    }
    public function search(Request $request){
        $student = Student::
        whereHas('user', function ($query) use ($request) {
                       $query->where('name', $request->name);
                   })
        ->orWhere('university_id' , $request->university_id)
                   ->first();
        if($student == null)
        return response()->json(['message' => 'invalid data'] , 400) ;
        $student['name'] = $student->user()->first()->name ;
        return $student ;
    }
}

/**
 * note :
 * row[0] -> number 
 * row[1] -> name 
 * row[2] -> international_id 
 * 
 * 
 * 
 */