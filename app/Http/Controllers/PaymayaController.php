<?php

namespace App\Http\Controllers;

use App\Enum\PaymentType;
use App\Models\Tithe;
use App\Models\Transaction;
use App\Services\ExceptionHandlerService;
use App\Services\PaymayaService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PaymayaController extends Controller
{
    public function generatePaymentLink(Request $request)
    {

        $validated = $request->validate([
            'transaction_id' => 'required|exists:transactions,id',
        ]);

        try {
            $user = Auth::user();
            if (!$user) {
                throw new Exception('Unauthenticated.');
            }

            $transaction = Transaction::findOrFail($validated['transaction_id']);
            $tithe = Tithe::where('transaction_id', $transaction->id)->first();

            if ($transaction->status !== 'pending') {
                throw new Exception(
                    sprintf('Invalid transaction status: %s. Expected status: pending.', $transaction->status),
                    400
                );
            }

            $paymentResponse = $this->processPayment($transaction, $user);
            $this->updateTransactionWithPayment($transaction, $paymentResponse);

            return response()->json([
                'status' => 'success',
                'data' => [
                    'payment_link' => $paymentResponse['redirectUrl'],
                ],
            ], 201);

        } catch (Exception $exception) {
            Log::error('Failed to generate payment link', [
                'transaction_id' => $request->transaction_id,
                'error' => $exception->getMessage(),
            ]);
            $exceptionService = new ExceptionHandlerService();
            return $exceptionService->handler($request, $exception);
        }
    }

    protected function processPayment($transaction, $user)
    {
        $paymayaService = new PaymayaService();

        $paymayaUserDetails = [
            'firstname' => $user->first_name,
            'lastname' => $user->last_name,
        ];

        $paymentRequestModel = $paymayaService->createRequestModel($transaction, $paymayaUserDetails);
        return $paymayaService->pay($paymentRequestModel);
    }

    protected function updateTransactionWithPayment($transaction, $paymentResponse)
    {
        $transaction->update([
            'checkout_id' => $paymentResponse['checkoutId'],
            'payment_link' => $paymentResponse['redirectUrl'],
        ]);
    }


}
