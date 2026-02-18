<?php

namespace App\Http\Controllers;

use App\Models\User;

class UserController extends Controller
{
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
}
