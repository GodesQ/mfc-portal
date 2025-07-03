<?php

namespace App\Http\Controllers;

use App\Enum\PaymentType;
use App\Models\EventRegistration;
use App\Models\Tithe;
use App\Models\Transaction;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function webhook(Request $request)
    {
        Log::info('Transaction', [$request->all()]);

        try {
            $transaction = Transaction::where('reference_code', $request->requestReferenceNumber)->first();

            $fundSource = $request->fundSource['type'] ?? 'N/A';

            if (!$transaction)
                throw new Exception("Transaction Not Found", 404);

            if ($request->status === "PAYMENT_SUCCESS" || $request->status === "AUTHORIZED") {
                $transaction->update([
                    'status' => 'paid',
                    'payment_mode' => $fundSource ?? 'maya',
                ]);

                Tithe::where('transaction_id', $transaction->id)->update([
                    'status' => 'paid',
                    'payment_mode' => $fundSource ?? 'maya',
                ]);
            }

            if ($request->status === "PAYMENT_FAILED") {
                $transaction->update([
                    'status' => 'failed',
                ]);
            }

            if ($request->status === "PAYMENT_CANCELLED") {
                $transaction->update([
                    'status' => 'cancelled',
                ]);
            }

            return 'ok';

        } catch (Exception $exception) {
            Log::error('Webhook Exception', [$exception]);
        }

    }
}
