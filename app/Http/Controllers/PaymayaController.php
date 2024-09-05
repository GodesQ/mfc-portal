<?php

namespace App\Http\Controllers;

use App\Enum\PaymentType;
use App\Models\Tithe;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymayaController extends Controller
{   
    public function checkout_success(Request $request) {
        // $reference_number = $request->requestReferenceNumber;

        // $transaction = Transaction::where('reference_code', $reference_number)->first();

        // abort_if(!$transaction, 404);

        // $transaction->update([
        //     'transaction_response_json' => json_encode($request->all()),
        //     'status' => 'paid',
        // ]);

        // if($transaction->payment_type === PaymentType::TITHE) {
        //     $tithe = Tithe::where('transaction_id', $transaction->id)->first();

        //     $tithe->update([
        //         'status' => "paid",
        //     ]);
        // }

        Log::info(json_encode($request->all()));

        return response(['message' => "OK"], 200);
    }

    public function checkout_failed(Request $request) {
        $reference_number = $request->requestReferenceNumber;

        $transaction = Transaction::where('reference_code', $reference_number)->first();

        abort_if(!$transaction, 404);

        $transaction->update([
            'transaction_response_json' => json_encode($request->all()),
            'status' => 'failed',
        ]);

        if($transaction->payment_type === PaymentType::TITHE) {
            $tithe = Tithe::where('transaction_id', $transaction->id)->first();

            $tithe->update([
                'status' => "failed",
            ]);
        }

        return response(['message' => "OK"], 200);
    }

    public function payment_success(Request $request) {
        Log::info(json_encode($request->all()));
        // $reference_number = $request->requestReferenceNumber;

        // $transaction = Transaction::where('reference_code', $reference_number)->first();

        // abort_if(!$transaction, 404);

        // $transaction->update([
        //     'transaction_response_json' => json_encode($request->all()),
        //     'status' => 'paid',
        // ]);

        // if($transaction->payment_type === PaymentType::TITHE) {
        //     $tithe = Tithe::where('transaction_id', $transaction->id)->first();

        //     $tithe->update([
        //         'status' => "paid",
        //     ]);
        // }

        return response(['message' => "OK"], 200);
    }


}
