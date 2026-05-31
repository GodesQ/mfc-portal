<?php

namespace App\Mail;

use App\Models\EventRegistration;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Throwable;

class EventRegistrationInvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public Transaction $transaction;

    public Collection $registrations;

    public function __construct(Transaction $transaction, Collection $registrations)
    {
        $this->transaction = $transaction;
        $this->registrations = $registrations;
    }

    public function build()
    {
        $event = $this->registrations->first()?->event;
        $eventTitle = $event?->title ?: 'your event';
        $filename = 'event-registration-invoice-' . ($this->transaction->reference_code ?: $this->transaction->transaction_code) . '.pdf';

        return $this
            ->subject("Your ticket invoice for {$eventTitle}")
            ->view('emails.event-registration-invoice')
            ->with([
                'transaction' => $this->transaction,
                'registrations' => $this->registrations,
                'eventTitle' => $eventTitle,
            ])
            ->attachData($this->pdfContent(), $filename, [
                'mime' => 'application/pdf',
            ]);
    }

    public function pdfViewData(): array
    {
        return [
            'transaction' => $this->transaction,
            'registrations' => $this->registrations->map(function (EventRegistration $registration) {
                $event = $registration->event;
                $ticket = $registration->ticket;
                $ticketPrice = $ticket ? ($ticket->is_free ? 0.00 : (float) $ticket->price) : (float) ($event?->reg_fee ?? 0);

                return [
                    'registration' => $registration,
                    'event' => $event,
                    'ticket' => $ticket,
                    'attendee_name' => $registration->display_name,
                    'booking_date' => optional($registration->created_at)->format('M d, Y') ?: 'N/A',
                    'booking_id' => $registration->registration_code ?: 'N/A',
                    'ticket_name' => $ticket?->ticket_name ?: 'Legacy Registration Fee',
                    'ticket_price' => $ticketPrice,
                    'early_bird_discount' => (float) $registration->early_bird_discount,
                    'total_paid' => (float) $registration->amount,
                    'ticket_image_data_uri' => $this->eventAssetDataUri($event?->ticket_image),
                    'ticket_logo_data_uri' => $this->eventAssetDataUri($event?->ticket_logo),
                    'ticket_instructions' => $this->sanitizeInstructions($event?->ticket_instructions),
                    'qr_code_data_uri' => $this->qrCodeDataUri($registration->registration_code),
                ];
            }),
        ];
    }

    private function pdfContent(): string
    {
        return Pdf::loadView('pdfs.event-registration-invoice', $this->pdfViewData())
            ->setPaper('a4', 'portrait')
            ->output();
    }

    private function eventAssetDataUri(?string $filename): ?string
    {
        if (blank($filename)) {
            return null;
        }

        $path = public_path('uploads/events/' . $filename);

        if (! File::exists($path)) {
            return null;
        }

        return 'data:' . File::mimeType($path) . ';base64,' . base64_encode(File::get($path));
    }

    private function qrCodeDataUri(?string $payload): ?string
    {
        if (blank($payload)) {
            return null;
        }

        try {
            $svg = QrCode::format('svg')
                ->size(140)
                ->margin(1)
                ->generate($payload);

            return 'data:image/svg+xml;base64,' . base64_encode((string) $svg);
        } catch (Throwable) {
            return null;
        }
    }

    private function sanitizeInstructions(?string $html): string
    {
        $html = (string) $html;
        $html = preg_replace('#<\s*(script|style|iframe|object|embed)[^>]*>.*?<\s*/\s*\1>#is', '', $html) ?? '';
        $html = preg_replace('/\s+on[a-z]+\s*=\s*(".*?"|\'.*?\'|[^\s>]+)/i', '', $html) ?? '';
        $html = preg_replace('/\s+(href|src)\s*=\s*([\'"])\s*javascript:.*?\2/i', '', $html) ?? '';

        return strip_tags(
            $html,
            '<p><br><strong><b><em><i><u><ul><ol><li><a><span><div><h1><h2><h3><h4><h5><h6><blockquote><table><thead><tbody><tr><th><td>'
        );
    }
}
