<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use Illuminate\Http\Request;

class CategorieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $AllCategories = Categorie::all();
        try {
            if (!$AllCategories) {
                return response()->json([
                    "status" => false,
                    "message" => "No all data Categorie"
                ]);
            } else {
                return response()->json([
                    "message" => "Success All Data",
                    "data" => $AllCategories
                ], 200);
            }
        } catch (\Throwable $th) {
            return response()->json([
                "status" => false,
                "message" => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        $categorieDetail = Categorie::findOrFail($id);
        try {
            if (!$categorieDetail) {
                return response()->json([
                    "status" => false,
                    "message" => "No all data Categorie"
                ]);
            } else {
                return response()->json([
                    "status" => true,
                    "message" => "Success Detail Data",
                    "data" => $categorieDetail
                ], 200);
            }
        } catch (\Throwable $th) {
            return response()->json([
                "status" => false,
                "message" => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "nama" => "required|string"
        ]);
        $CreateCategorie = Categorie::create([
            "nama" => $request->nama
        ]);

        try {
            if (!$CreateCategorie) {
                return response()->json([
                    "message" => "Not Create Categories"
                ]);
            } else {
                return response()->json([
                    "status" => true,
                    "message" => "Succes Create Categories"
                ], 200);
            }
        } catch (\Throwable $th) {
            return response()->json([
                "status" => false,
                "messagge" => $th->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Categorie $categorie)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Categorie $categorie)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $UpdateCategorie = Categorie::findOrFail($id);
        $request->validate([
            "nama" => "required|string"
        ]);

        $UpdateCategorie->update([
            "nama" => $request->nama
        ]);
        try {
            if (!$UpdateCategorie) {
                return response()->json([
                    "status" => false,
                    "message" => "No all data Categorie"
                ]);
            } else {
                return response()->json([
                    "status" => true,
                    "message" => "Success Detail Data",
                    "data" => $UpdateCategorie
                ], 200);
            }
        } catch (\Throwable $th) {
            return response()->json([
                "status" => false,
                "message" => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $DeleteCategorie = Categorie::destroy($id);
        try {
            if (!$DeleteCategorie) {
                return response()->json([
                    "status" => false,
                    "message" => "No Delete Categorie"
                ]);
            } else {
                return response()->json([
                    "message" => "Success Delete Data Categories",
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
