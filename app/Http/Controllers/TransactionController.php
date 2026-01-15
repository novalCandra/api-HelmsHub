<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transactionAll = Transaction::paginate(10);
        return response()->json([
            "status" => true,
            "message" => "All Transaction",
            "data" => $transactionAll
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "borrowed_id" => "required|exists:borroweds,id",
            "helm_id" => "required|exists:helms,id",
            "total_price" => "required|numeric",
            "total_price" => "required|numeric",
            "fine_amount" => "required|numeric",
            "payment_method" => "required|string",
            "payment_status" => "required|string",
            "payment_date" => "required|date"
        ]);

        $user = Auth::user();
        $CreateTransaksi = Transaction::create([
            "borrowed_id" => $request->borrowed_id,
            "helm_id" => $request->helm_id,
            "user_id" => $user->id,
            "total_price" => $request->total_price,
            "fine_amount" => $request->fine_amount,
            "payment_method" => $request->payment_method,
            "payment_status" => $request->payment_status,
            "payment_date" => $request->payment_date
        ]);

        return response()->json([
            "status" => true,
            "message" => "success transaksi users",
            "data" => $CreateTransaksi
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $TransactionId = Transaction::findOrFail($id);
        return response()->json([
            "status" => true,
            "message" => "details Transaction",
            "data" => $TransactionId
        ], 201);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        //
    }
}
