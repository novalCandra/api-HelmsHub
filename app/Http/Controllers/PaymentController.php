<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

use function Symfony\Component\Clock\now;

class PaymentController extends Controller
{
    public function webHook(Request $request)
    {
        $externalId = $request->external_id;
        $status = $request->status;

        $transaction = Transaction::where('external_id', $externalId)->first();

        if ($transaction && $status === "PAID") {
            $transaction->update([
                'payment_status' => 'paid',
                'payment_date' => now()
            ]);
        }

        return response()->json(['success' => true]);
    }

    public function hanldeBook(Request $request)
    {
        $externalId = $request->external_id;
        $status = $request->status;

        if ($status === "PAID") {
            $transaction = Transaction::where('external_id', $externalId)->first();

            if ($transaction) {
                $transaction->update([
                    'payment_status' => 'paid'
                ]);

                $transaction->borrowed->update([
                    "status" => 'completed'
                ]);
            }
        }

        return response()->json(['message' => 'Webhook received']);
    }
}
