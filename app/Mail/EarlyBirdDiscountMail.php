<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EarlyBirdDiscountMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $recipientName;

    public string $eventTitle;

    public float $discount;

    public function __construct(string $recipientName, string $eventTitle, float $discount)
    {
        $this->recipientName = $recipientName;
        $this->eventTitle = $eventTitle;
        $this->discount = $discount;
    }

    public function build()
    {
        return $this->subject('You claimed an Early Bird discount')
            ->view('emails.early_bird_discount');
    }
}
