<?php

namespace App\Mail;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class TithePaymentMail extends Mailable
{
    use Queueable, SerializesModels;

    public Transaction $transaction;

    public Collection $tithes;

    public function __construct(Transaction $transaction, Collection $tithes)
    {
        $this->transaction = $transaction;
        $this->tithes = $tithes;
    }

    public function build()
    {
        return $this
            ->subject('Thank You for Your Tithe')
            ->view('emails.tithe-payment')
            ->with([
                'transaction' => $this->transaction,
                'tithes' => $this->tithes,
                'logoPath' => public_path('build/images/MFC-Logo.jpg'),
            ]);
    }
}
