<?php

namespace App\Http\Controllers;

use App\Enum\PaymentType;
use App\Mail\EventRegistrationInvoiceMail;
use App\Mail\TithePaymentMail;
use App\Models\EventRegistration;
use App\Models\Tithe;
use App\Models\Transaction;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class RedirectController extends Controller
{
    function payment_success(Request $request)
    {
        try {
            $transaction = Transaction::where('transaction_code', $request->transaction_id)->firstOrFail();

            $items = [];

            $fundSource = $request->fundSource['type'] ?? 'maya';

            $transaction->update([
                'status' => 'paid',
                'payment_mode' => $fundSource ?? 'maya',
            ]);

            if ($transaction->payment_type == PaymentType::EVENT_REGISTRATION) {
                $eventRegistrations = EventRegistration::where('transaction_id', $transaction->id)
                    ->with('user', 'event', 'ticket', 'event_user_detail', 'transaction')
                    ->get();

                $this->sendEventRegistrationInvoice($transaction, $eventRegistrations);

                $items = $eventRegistrations
                    ->map(function ($row) {
                        return [
                            'id' => $row->id,
                            'name' => $row->display_name,
                            'mfc_id_number' => $row->display_mfc_id_number,
                            'payment_type' => "Event Registration",
                            'event' => $row->event,
                            'date' => Carbon::parse($row->created_at)->format('M d, Y'),
                            'amount' => $row->amount,
                        ];
                    })->toArray();
            }

            if ($transaction->payment_type == PaymentType::TITHE) {
                $tithes = Tithe::where('transaction_id', $transaction->id)
                    ->with('user')
                    ->get();

                $items = $tithes->map(function ($row) {
                    return [
                        'id' => $row->id,
                        'name' => ($row->user->first_name ?? " ") . ' ' . ($row->user->last_name ?? " "),
                        'mfc_id_number' => ($row->user->mfc_id_number ?? " "),
                        'payment_type' => "Tithe",
                        'event' => null,
                        'date' => Carbon::parse($row->created_at)->format('M d, Y'),
                        'amount' => $row->amount,
                    ];
                });

                Tithe::where('transaction_id', $transaction->id)->update([
                    'status' => 'paid',
                    'payment_mode' => $fundSource ?? 'maya',
                ]);

                $this->sendTithePaymentEmail($transaction, $tithes);
            }

            return view('pages.payments.redirect-success', compact('transaction', 'items'));
        } catch (Exception $exception) {
            Log::error('Payment Success Exception', [$exception]);
            abort(500);
        }
    }

    function payment_failed(Request $request)
    {
        try {
            return view('pages.payments.redirect-failed');
        } catch (Exception $exception) {
            Log::error('Payment Failed Exception', [$exception]);
            abort(500);
        }
    }

    function payment_canceled(Request $request)
    {
        try {
            return view('pages.payments.redirect-canceled');
        } catch (Exception $exception) {
            Log::error('Payment Canceled Exception', [$exception]);
            abort(500);
        }
    }

    private function sendEventRegistrationInvoice(Transaction $transaction, Collection $registrations): void
    {
        if (
            $transaction->payment_type !== PaymentType::EVENT_REGISTRATION
            || blank($transaction->payer_email)
            || $transaction->invoice_emailed_at
            || $registrations->isEmpty()
        ) {
            return;
        }

        try {
            Mail::to($transaction->payer_email)->send(
                new EventRegistrationInvoiceMail($transaction, $registrations)
            );

            $transaction->update([
                'invoice_emailed_at' => now(),
            ]);
        } catch (Throwable $exception) {
            Log::error('Event registration invoice email failed', [
                'transaction_id' => $transaction->id,
                'error' => $exception->getMessage(),
            ]);

            report($exception);
        }
    }

    private function sendTithePaymentEmail(Transaction $transaction, Collection $tithes): void
    {
        if (
            $transaction->payment_type !== PaymentType::TITHE
            || blank($transaction->payer_email)
            || $transaction->invoice_emailed_at
            || $tithes->isEmpty()
        ) {
            return;
        }

        try {
            Mail::to($transaction->payer_email)->send(
                new TithePaymentMail($transaction, $tithes)
            );

            $transaction->update([
                'invoice_emailed_at' => now(),
            ]);
        } catch (Throwable $exception) {
            Log::error('Tithe payment email failed', [
                'transaction_id' => $transaction->id,
                'error' => $exception->getMessage(),
            ]);

            report($exception);
        }
    }
}
