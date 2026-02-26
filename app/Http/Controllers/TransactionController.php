<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Xendit\Configuration;
use Xendit\Invoice\CreateInvoiceRequest;
use Xendit\Invoice\InvoiceApi;

class TransactionController extends Controller
{

    protected InvoiceApi $invoicapi;

    public function __construct()
    {
        Configuration::setXenditKey(
            config('services.xendit.secret_key')
        );

        $this->invoicapi = new InvoiceApi();
    }
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

        try {
            $request->validate([
                "borrowed_id" => "required|exists:borroweds,id",
                "helm_id" => "required|exists:helms,id",
                "amount" => "required|numeric|min:1000",
                "email" => "required|email"
            ]);

            $externalId = 'INV-' . uniqid();
            $user = Auth::user();
            $CreateTransaksi = Transaction::create([
                "borrowed_id" => $request->borrowed_id,
                "helm_id" => $request->helm_id,
                "user_id" => $user->id,
                "total_price" => $request->amount,
                "fine_amount" => 0,
                "payment_method" => 'transfer',
                "payment_status" => 'pending',
            ]);

            // Invoice Xendit
            $createInvoice = new CreateInvoiceRequest([
                'external_id' => $externalId,
                'amount' => $request->amount,
                'payer_email' => $request->email,
                'description' => "pembayaran helm",
                // kembali ke webiste
                'success_redirect_url' => 'http://localhost:3000/users/home',
                'failure_redirect_url' => 'http://localhost:3000/payment/failed',
            ]);

            $invoice = $this->invoicapi->createInvoice($createInvoice);
            // Update Transaction
            $CreateTransaksi->update([
                'xendit_invoice_id' => $invoice['id'],
                'invoice_url' => $invoice['invoice_url']
            ]);

            return response()->json([
                "status" => true,
                "message" => "success transaksi users",
                "data" => $CreateTransaksi
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => false,
                "message" => $th->getMessage()
            ], 500);
        }
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
