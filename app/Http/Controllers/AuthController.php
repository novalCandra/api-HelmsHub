<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function profile()
    {
        $userProfile = Auth::user();
        return response()->json([
            "status" => true,
            "message" => "Sucess menampilkan data Profile",
            "data" => $userProfile
        ], 200);
    }

    public function login(Request $request)
    {
        $request->validate([
            "email" => "required|string|min:1|max:255",
            "password" => "required|string|min:1|max:255"
        ]);

        $user = User::where('email' . $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                "message" => ["email tidak ada di database"]
            ]);
        }

        $token = $user->createToken('default')->plainTextToken;
        $user->token = $token;

        return response()->json([
            "status" => true,
            "message" => "Success Login",
            "data" => $user
        ], 201);
    }

    public function register(Request $request)
    {
        $request->validate([
            "full_name" => "required|string|min:1|max:255",
            "email" => "required|string|min:1|max:255",
            "password" => "required|string|min:1|max:255",
            "phone_number" => ["required", "string", "regex:#^(\+62|0)[0-9]{9,13}$#"]
        ]);

        $createRegister = User::create([
            "full_name" => $request->full_name,
            "email" => $request->email,
            "password" => $request->password,
            "phone_number" => $request->phone_number
        ]);

        return response()->json([
            "status" => true,
            "message" => "success Register",
            "data" => $createRegister
        ], 201);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json([
            "message" => "success Logout Account"
        ], 201);
    }
}
