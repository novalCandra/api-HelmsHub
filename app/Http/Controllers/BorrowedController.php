<?php

namespace App\Http\Controllers;

use App\Models\Borrowed;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Xendit\Configuration;
use Xendit\Invoice\CreateInvoiceRequest;
use Xendit\Invoice\InvoiceApi;

class BorrowedController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    // Create Invoice Xendit
    protected InvoiceApi $invoicapi;

    public function __construct()
    {
        Configuration::setXenditKey(
            config('services.xendit.secret_key')
        );

        $this->invoicapi = new InvoiceApi();
    }


    public function index()
    {
        $BorrowedAll = Borrowed::with(['Users', 'helm'])->get();

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

        $borrowed = Borrowed::with(['Users', 'helm'])->where('user_id', $user->id)->where('payment_status', 'borrowed')->latest()->first();

        // Total Helm yang di pinjam
        $totalBorrowed = Borrowed::with('user_id', $user->id)->where('payment_status', 'borrowed')->count();

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
            "helm_id" => "required|exists:helms,id",
            "borrow_date" => "required|date",
            "due_date" => "nullable|date",
            "return_date" => "required|date",
        ]);

        $user = Auth::user();

        $BorrowCreated = Borrowed::create([
            "user_id" => $user->id,
            "helm_id" => $request->helm_id,
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
        $borrowedDetails = Borrowed::with(['Users', 'helm'])->findOrFail($id);
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

        if ($ReturnBorrowed->payment_status !== "borrowed") {
            return response()->json([
                "message" => "This helmet is still on loan."
            ], 403);
        }
        $ReturnBorrowed->update([
            "payment_status" => "returned"
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

    public function returnBorrowed($id)
    {
        $user = Auth::user();
        $borrowed = Borrowed::with('helm')->where('user_id', $user->id)->where('payment_status', 'borrowed')->findOrFail($id);


        $today = Carbon::now();
        $borrowDate =  Carbon::parse($borrowed->borrow_date);
        $returnDate = Carbon::parse($borrowed->return_date);


        // Hitung jumlah
        $totalDays = $borrowDate->diffInDays($today);
        $dailyPrice = $borrowed->helm->daily_price;

        $lateFree = 0;

        if ($today->gt($returnDate)) {
            $lateDays = $returnDate->diffInDays($today);
            $lateFree = $lateDays * $borrowed->helm->late_free_per_day;
        }

        $totalAmount = ($totalDays * $dailyPrice) + $lateFree;

        // Create Transaksi
        $transaction = Transaction::create([
            'borrowed_id' => $borrowed->id,
            'user_id' => $user->id,
            'helm_id' => $borrowed->helm_id,
            'total_price' => $totalAmount,
            'fine_amount' => $lateFree,
            'payment_status' => 'pending',
            'payment_date' => now(),
        ]);

        // Upaate status jadi waiting payment
        $borrowed->update([
            'payment_status' => 'paid'
        ]);

        $createInvoiceRequest = new CreateInvoiceRequest([
            'external_id' => 'TRX-' . $transaction->id,
            'amount' => $totalAmount,
            'payer_email' => Auth::user()->email,
            'description' => 'Payment for helmet return',
        ]);

        $invoice = $this->invoicapi->createInvoice($createInvoiceRequest);
        return response()->json([
            'invoice_url' => $invoice['invoice_url'],
            'total_amount' => $totalAmount
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Borrowed $borrowed)
    {
        //
    }
}
