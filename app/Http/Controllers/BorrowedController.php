<?php

namespace App\Http\Controllers;

use App\Models\Borrowed;
use App\Models\Helm;
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
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "helmet_id" => "required|exists:helms,id",
            "borrow_date" => "required|date",
            "due_date" => "required|date",
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
    public function update(Request $request, Borrowed $borrowed)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Borrowed $borrowed)
    {
        //
    }
}
