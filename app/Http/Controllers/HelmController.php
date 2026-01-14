<?php

namespace App\Http\Controllers;

use App\Models\Helm;
use Illuminate\Http\Request;

class HelmController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $helmAll = Helm::paginate(10);
        return response()->json([
            "status" => true,
            "message" => "succes all data Helms",
            "data" => $helmAll
        ], 200);
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
            "helmet_name" => "required|string|min:1|max:255",
            "condition" => "required|string|min:1|max:255",
            "status" => "required|string|min:1|max:255",
            "daily_price" => "required|numeric",
            "late_fee_per_day" => "required|numeric",
        ]);

        $HelmCreate = Helm::create([
            "helmet_name" => $request->helmet_name,
            "condition" => $request->condition,
            "status" => $request->status,
            "daily_price" => $request->daily_price,
            "late_fee_per_day" => $request->late_fee_per_day
        ]);

        return response()->json([
            "status" => true,
            "message" => "success add Helms",
            "data" => $HelmCreate
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $UserDetail = Helm::findOrFail($id);
        return response()->json([
            "status" => true,
            "message" => "Succes Details Helms",
            "data" => $UserDetail
        ], 201);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Helm $helm)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $HelmUpdate = Helm::findOrFail($id);
        $request->validate([
            "helmet_name" => "required|string|min:1|max:255",
            "condition" => "required|string|min:1|max:255",
            "status" => "required|string|min:1|max:255",
            "daily_price" => "required|numeric",
            "late_fee_per_day" => "required|numeric",
        ]);

        $HelmUpdate->update([
            "helmet_name" => $request->helmet_name,
            "condition" => $request->condition,
            "status" => $request->status,
            "daily_price" => $request->daily_price,
            "late_fee_per_day" => $request->late_fee_per_day
        ]);

        return response()->json([
            "status" => true,
            "message" => "success edit data helms",
            "data" => $HelmUpdate
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $HelmDelete = Helm::destroy($id);
        return response()->json([
            "message" => "success delete data"
        ], 200);
    }
}
