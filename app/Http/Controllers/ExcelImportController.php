<?php

namespace App\Http\Controllers;

use App\Models\Student;
use mysqli;
use PDO;
use SimpleSoftwareIO\QrCode\Facades\QrCode ;

class ExcelImportController extends Controller
{
    public function test(){
      $servername = "localhost";
      $username = "root";
      $password = "";
      $dbname = "code_education";

      $conn = new mysqli($servername, $username, $password, $dbname , 3310);
      if ($conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
      }
      $sql = "SELECT * FROM users;";
      $result = $conn->query($sql);
      return $result ;
    }
    public function test2(){
      $student = Student::find(1);
      $qrcode = QrCode::size(300)->generate($student->university_id);
      return base64_encode($qrcode) ;
    }
}
