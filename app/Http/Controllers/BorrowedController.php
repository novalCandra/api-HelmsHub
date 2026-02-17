<?php

namespace App\Http\Controllers;

use App\Models\Borrowed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BorrowedController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $BorrowedAll = Borrowed::with(['Users', 'Helm'])->get();

        return response()->json([
            "status" => true,
            "message" => "Success add Borrowed All",
            "data" => $BorrowedAll
        ],  200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function profileMe()
    {
        $user = Auth::user();

        $borrowed = Borrowed::with(['Users', 'Helm'])->where('user_id', $user->id)->where('status', 'borrowed')->latest()->first();

        // Total Helm yang di pinjam
        $totalBorrowed = Borrowed::with('user_id', $user->id)->where('status', 'borrowed')->count();

        $borrowed->totalBorrowed = $totalBorrowed;
        try {
            if (!$borrowed) {
                return response()->json([
                    "status" => false,
                    "message" => "You don't have any borrowed items",
                    "total_borrowed" => 0
                ]);
            } else {
                return response()->json([
                    "status" => true,
                    "message" => "Success get Borrowed",
                    "data" => $borrowed
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

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "helmet_id" => "required|exists:helms,id",
            "borrow_date" => "required|date",
            "due_date" => "nullable|date",
            "return_date" => "required|date",
        ]);

        $user = Auth::user();

        $BorrowCreated = Borrowed::create([
            "user_id" => $user->id,
            "helmet_id" => $request->helmet_id,
            "borrow_date" => $request->borrow_date,
            "due_date" => $request->due_date,
            "return_date" => $request->return_date
        ]);

        return response()->json([
            "status" => true,
            "message"  => "Success add Browwed",
            "data" => $BorrowCreated
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $borrowedDetails = Borrowed::with(['Users', 'Helm'])->findOrFail($id);
        return response()->json([
            "status" => true,
            "message" => "success details Borrowed",
            "data" => $borrowedDetails
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Borrowed $borrowed)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id)
    {
        $ReturnBorrowed = Borrowed::findOrFail($id);

        if ($ReturnBorrowed->status !== "borrowed") {
            return response()->json([
                "message" => "This helmet is still on loan."
            ], 403);
        }
        $ReturnBorrowed->update([
            "status" => "returned"
        ]);

        try {
            if (!$ReturnBorrowed) {
                return response()->json([
                    "status" => false,
                    "message" => "Cannot return a helmet"
                ], 402);
            } else {
                return response()->json([
                    "status" => true,
                    "message" => "Sucess Returning a Helmet"
                ], 201);
            }
        } catch (\Throwable $th) {
            return response()->json([
                "status" => false,
                "message" => "api not found",
                "error" => $th->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Borrowed $borrowed)
    {
        //
    }
}
