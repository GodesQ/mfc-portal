<?php

namespace App\Services;

use App\Models\Tithe;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TitheService
{
    public function getUserTithes($request, $user)
    {
        $query = Tithe::query();

        $query->where('mfc_user_id', $user->mfc_id_number);

        // Date range filter
        if ($request->has('date_start') && $request->has('date_end')) {
            $query->whereBetween('created_at', [
                $request->date_start,
                $request->date_end
            ]);
        } elseif ($request->has('date')) {
            $query->whereDate('created_at', $request->date);
        }

        // Month range filter
        if ($request->has('month_start') && $request->has('month_end')) {
            $start = Carbon::parse($request->month_start)->startOfMonth();
            $end = Carbon::parse($request->month_end)->startOfMonth();

            $months = [];
            while ($start <= $end) {
                $months[] = $start->format('F'); // "F" gives full month name like "May"
                $start->addMonth();
            }

            $query->whereIn('for_the_month_of', $months);

        } elseif ($request->has('month')) {
            $monthName = Carbon::parse($request->month)->format('F');
            $query->where('for_the_month_of', $monthName);
        }

        if ($request->filled('min_amount') && $request->filled('max_amount')) {
            $minAmount = (int) $request->min_amount;
            $maxAmount = (int) $request->max_amount;
            $query->whereBetween('amount', [$minAmount, $maxAmount]);
        }

        return $query->get();
    }

    public function store($request)
    {
        DB::beginTransaction();

        try {
            $user = $this->findUserOrFail($request->mfc_user_id);
            $transaction = $this->storeTransaction($request);
            $tithe = $this->createTithe($request, $user, $transaction);

            if ($this->paymentRequired($request)) {
                $paymentResponse = $this->processPayment($transaction, $user);
                $this->updateTransactionWithPayment($transaction, $paymentResponse);

                DB::commit();

                return $this->buildSuccessResponse($transaction, $tithe, $paymentResponse['redirectUrl']);
            }

            DB::commit();
            return $this->buildSuccessResponse($transaction, $tithe, null);

        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    protected function findUserOrFail($mfcUserId)
    {
        $authUser = Auth::user();
        $user = User::where('mfc_id_number', $mfcUserId)->first();

        if (!$user) {
            throw new Exception("User not found based on your mfc user id.", 404);
        }

        if ($user->id !== $authUser->id) {
            throw new Exception("Invalid user. Please try again.", 400);
        }

        return $user;
    }

    protected function createTithe($request, $user, $transaction)
    {
        return Tithe::create([
            "mfc_user_id" => $user->mfc_id_number,
            "transaction_id" => $transaction->id,
            "payment_mode" => "N/A",
            "amount" => $request->amount,
            "for_the_month_of" => $request->for_the_month_of,
            "status" => $this->paymentRequired($request) ? "unpaid" : "paid",
        ]);
    }

    protected function paymentRequired($request)
    {
        return $request->is_payment_required == 1;
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

    protected function buildSuccessResponse($transaction, $tithe, $paymentUrl)
    {
        return [
            'transaction' => $transaction,
            'tithe' => $tithe,
            'payment_url' => $paymentUrl,
        ];
    }

    protected function storeTransaction($request)
    {
        $transaction_code = generateTransactionCode();
        $reference_code = generateReferenceCode();

        $convenience_fee = $request->amount >= 50 ? 10 : 0;
        $total_amount = $request->amount + $convenience_fee;

        $transaction = Transaction::create([
            "transaction_code" => $transaction_code,
            "reference_code" => $reference_code,
            "convenience_fee" => $convenience_fee,
            "received_from_id" => auth()->user()->id,
            "sub_amount" => $request->amount,
            "total_amount" => $total_amount,
            "payment_mode" => $request->is_payment_required ? "N/A" : "cash",
            "payment_type" => "tithe",
            "status" => "pending",
        ]);

        return $transaction;
    }
}
