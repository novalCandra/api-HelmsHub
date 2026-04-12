<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function CreateUser(Request $request)
    {
        $request->validate([
            "full_name" => "required|string|min:1|max:255",
            "email" => "required|string|min:1|max:255",
            "password" => "required|string|min:1|max:255",
            "phone_number" => ["required", "string", "regex:#^(\+62|0)[0-9]{9,13}$#"]
        ]);
        $CreateAccount = User::create([
            "full_name" => $request->full_name,
            "email" => $request->email,
            "password" => Hash::make($request->password),
            "phone_number" => $request->phone_number
        ]);

        try {
            if (!$CreateAccount) {
                return response()->json([
                    "message" => "not Create Account"
                ]);
            } else {
                return response()->json([
                    "message" => "Success",
                    "data" => $CreateAccount
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                "message" => $th->getMessage()
            ]);
        }
    }
    public function manageUsers()
    {
        $manageUsers = User::where('role', 'user')->get();
        try {
            if (!$manageUsers) {
                return response()->json([
                    "status" => false,
                    "message" => "You don't have any borrowed items",
                ]);
            } else {
                return response()->json([
                    "status" => true,
                    "message" => "Success get manage Users",
                    "data" => $manageUsers
                ], 200);
            }
        } catch (\Throwable $th) {
            return response()->json([
                "status" => false,
                "message" => "api not found",
                "error" => $th->getMessage()
            ]);
        }
    }

    public function bannedUsers($id)
    {
        $bannedUsers = User::findOrFail($id);
        $bannedUsers->delete();
        try {
            if (!$bannedUsers) {
                return response()->json([
                    "status" => false,
                    "message" => "cannot ban users"
                ], 402);
            } else {
                return response()->json([
                    "status" => true,
                    "message" => "Successful Banned Users"
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                "status" => false,
                "message" => "api not found",
                "error" => $th->getMessage()
            ]);
        }
    }

    public function updateUsers(Request $request, $id)
    {
        $updateDataUsers = User::findOrFail($id);

        $request->validate([
            "full_name" => "required|string|min:1|max:255",
            "email" => "required|string|min:1|max:255",
            "password" => "nullable|string|min:1|max:255",
            "phone_number" => ["required", "string", "regex:#^(\+62|0)[0-9]{9,13}$#"]
        ]);

        $updateDataUsers->update([
            "full_name" => $request->full_name,
            "email" => $request->email,
            "phone_number" => $request->phone_number
        ]);
        try {
            if (!$updateDataUsers) {
                return response()->json([
                    "status" => false,
                    "message" => "not Update users"
                ], 402);
            } else {
                return response()->json([
                    "status" => true,
                    "message" => "Success Update users",
                    "data" => $updateDataUsers
                ], 200);
            }
        } catch (\Throwable $th) {
            return response()->json([
                "status" => false,
                "message" => $th->getMessage()
            ], 500);
        }
    }

    public function deleteData($id)
    {
        $DeleteDatUsers = User::destroy($id);
        try {
            if (!$DeleteDatUsers) {
                return response()->json([
                    "status" => false,
                    "message" => "not delete users"
                ], 402);
            } else {
                return response()->json([
                    "status" => true,
                    "message" => "Success Delete users"
                ], 200);
            }
        } catch (\Throwable $th) {
            return response()->json([
                "status" => false,
                "message" => $th->getMessage()
            ], 500);
        }
    }

    public function detailUsers($id)
    {
        $DetailUser = User::findOrFail($id);
        try {
            if (!$DetailUser) {
                return response()->json([
                    "status" => false,
                    "message" => "tidak bisa detail users"
                ], 402);
            } else {
                return response()->json([
                    "status" => true,
                    "message" => "success detail data",
                    "data" => $DetailUser
                ], 200);
            }
        } catch (\Throwable $th) {
            return response()->json([
                "status" => false,
                "message" => $th->getMessage()
            ], 500);
        }
    }
}
