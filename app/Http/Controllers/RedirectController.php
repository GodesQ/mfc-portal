<?php

namespace App\Http\Controllers;

use App\Enum\PaymentType;
use App\Models\EventRegistration;
use App\Models\Tithe;
use App\Models\Transaction;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class RedirectController extends Controller
{
    function payment_success(Request $request) {
        try {
            $transaction = Transaction::where('transaction_code', $request->transaction_id)->first();

            $items = [];

            if($transaction->payment_type == PaymentType::EVENT_REGISTRATION) {
                $items = EventRegistration::where('transaction_id', $transaction->id)->get()->map(function ($row) {
                    return [
                        'id' => $row->id,
                        'name' => ($row->user->first_name ?? " ") . ' ' . ($row->user->last_name ?? " "),
                        'mfc_id_number' => ($row->user->mfc_id_number ?? " "),
                        'payment_type' => "Event Registration",
                        'date' => Carbon::parse($row->created_at)->format('M d, Y'),
                        'amount' => $row->amount,
                    ];
                })->toArray();
            }

            if($transaction->payment_type == PaymentType::TITHE) {
                $items = Tithe::where('transaction_id', $transaction->id)->get()->map(function ($row) {
                    return [
                        'id' => $row->id,
                        'name' => ($row->user->first_name ?? " ") . ' ' . ($row->user->last_name ?? " "),
                        'mfc_id_number' => ($row->user->mfc_id_number ?? " "),
                        'payment_type' => "Tithe",
                        'date' => Carbon::parse($row->created_at)->format('M d, Y'),
                        'amount' => $row->amount,
                    ];
                });
            }


            if(config('app.env') === 'development') {
                $transaction->update([
                    'status' => 'paid', 
                ]);
            }

            return view('pages.payments.redirect-success', compact('transaction', 'items'));
        } catch (Exception $exception) {
            abort(500);
        }
    }
}
