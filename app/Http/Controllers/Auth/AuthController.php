<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\SetOfStudent;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Ramsey\Uuid\Type\Integer;

class AuthController extends Controller
{
    public function login (Request $request) {
        $validatedData = $request->validate([
            'email' => ['required'],
            'password' => 'required'
        ]);
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $user = auth()->user();
            $token = $user->createToken('Personal Token')->plainTextToken;
            return response()->json([
                'role' => $user->role,
                'token' => $token
            ]);
        }
        return response()->json([
            'error' => 'incorrect information' ,
        ],401);
    }
}
