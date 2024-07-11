<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Support\Facades\Storage;
use mysqli;
use PDO;

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
      // $files = Storage::files('backups');
        $files = Storage::disk('local')->files('public/backups');
       
          $fileUrls = array_map(function ($file) {
            return url('/storage/backups/' . basename($file));
        }, $files);

        // Return the list of files as a JSON response
        return response()->json($fileUrls);
    }
}
