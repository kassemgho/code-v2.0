<?php

namespace App\Http\Controllers\Adminstrator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BackupController extends Controller
{
    public function index(){
       $files = Storage::disk('local')->files('public/backups');
       
          $fileUrls = array_map(function ($file) {
            return [
                'name' => basename($file) ,
                'url' => url('/storage/backups/' . basename($file))
            ] ;
        }, $files);
        $fileUrls = array_reverse($fileUrls);
        // Return the list of files as a JSON response
        return response()->json($fileUrls);
    }
    public function getByName($year){
        $files = Storage::disk('local')->files("public/backups");
        foreach($files as $file){
            $baseName = substr(basename($file), 0, 4); // Get the first 4 characters (year part)
            if ((int)$baseName == $year)
                    return response()->json(url('/storage/backups/' . basename($file)));
        }
        return response()->json([
            'message' => 'there is no backup in this year'
        ],400);
    }
}
