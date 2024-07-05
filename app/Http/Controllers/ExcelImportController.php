<?php

namespace App\Http\Controllers;

use App\Models\Student;
use SimpleSoftwareIO\QrCode\Facades\QrCode ;

class ExcelImportController extends Controller
{
    public function test(){
      $student = Student::find(1);
      $qrcode = QrCode::size(300)->generate($student->university_id);
      return base64_encode($qrcode) ;
    }
}
