<?php

namespace Tests\Feature;

use App\Enum\PaymentType;
use App\Mail\EventRegistrationInvoiceMail;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\Ticket;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class EventRegistrationInvoiceEmailTest extends TestCase
{
    use RefreshDatabase;

    public function test_payment_success_sends_event_registration_invoice_once_to_payer(): void
    {
        Mail::fake();

        [$transaction] = $this->createPaidRegistrationRecords();

        $this->get(route('payments.success', [
            'transaction_id' => $transaction->transaction_code,
        ]))->assertOk();

        Mail::assertSent(EventRegistrationInvoiceMail::class, function (EventRegistrationInvoiceMail $mail) {
            return $mail->hasTo('payer@example.com');
        });
        Mail::assertSent(EventRegistrationInvoiceMail::class, 1);
        $this->assertNotNull($transaction->fresh()->invoice_emailed_at);

        $this->get(route('payments.success', [
            'transaction_id' => $transaction->transaction_code,
        ]))->assertOk();

        Mail::assertSent(EventRegistrationInvoiceMail::class, 1);
    }

    public function test_invoice_pdf_view_renders_with_and_without_event_assets(): void
    {
        [$transaction, $registration] = $this->createPaidRegistrationRecords([
            'ticket_instructions' => '<p><strong>Bring this ticket.</strong></p><script>alert("x")</script>',
        ]);

        $mail = new EventRegistrationInvoiceMail($transaction, collect([$registration->load('user', 'event', 'ticket', 'event_user_detail', 'transaction')]));

        $html = view('pdfs.event-registration-invoice', $mail->pdfViewData())->render();

        $this->assertStringContainsString('Bring this ticket.', $html);
        $this->assertStringNotContainsString('<script>', $html);
        $this->assertStringContainsString($registration->registration_code, $html);
    }

    private function createPaidRegistrationRecords(array $eventOverrides = []): array
    {
        $user = User::create([
            'first_name' => 'James',
            'last_name' => 'Garnfil',
            'email' => 'attendee@example.com',
            'password' => bcrypt('password'),
            'mfc_id_number' => 'MFCPH000001',
            'contact_number' => '09170000001',
            'email_verified_at' => now(),
        ]);

        $event = Event::create(array_merge([
            'title' => 'AIRA UNLEASHED: Official Launch of AIRA Command Center',
            'type' => '1',
            'section_ids' => [1],
            'start_date' => '2026-02-26',
            'end_date' => '2026-02-26',
            'time' => '17:00:00',
            'location' => 'The Podium Hall, Ortigas Center',
            'reg_fee' => 0,
            'description' => '<p>Launch event.</p>',
            'ticket_instructions' => '<p>Present this ticket at registration.</p>',
            'status' => 'Active',
        ], $eventOverrides));

        $ticket = Ticket::create([
            'event_id' => $event->id,
            'ticket_name' => 'Free Pass',
            'price' => 0,
            'is_free' => true,
            'is_unlimited' => true,
        ]);

        $transaction = Transaction::create([
            'transaction_code' => 'TRX-TEST-001',
            'reference_code' => 'REF-TEST-001',
            'received_from_id' => $user->id,
            'ticket_id' => $ticket->id,
            'payer_first_name' => 'James',
            'payer_last_name' => 'Garnfil',
            'payer_email' => 'payer@example.com',
            'payer_contact_number' => '09170000002',
            'donation' => 0,
            'convenience_fee' => 0,
            'sub_amount' => 0,
            'early_bird_discount' => 0,
            'total_amount' => 0,
            'payment_mode' => 'N/A',
            'payment_type' => PaymentType::EVENT_REGISTRATION,
            'status' => 'pending',
        ]);

        $registration = EventRegistration::create([
            'registration_code' => 'REG26-02-ABC1234',
            'transaction_id' => $transaction->id,
            'event_id' => $event->id,
            'ticket_id' => $ticket->id,
            'user_id' => $user->id,
            'mfc_id_number' => $user->mfc_id_number,
            'amount' => 0,
            'early_bird_discount' => 0,
            'registered_by' => $user->id,
            'registered_at' => now(),
        ]);

        return [$transaction, $registration];
    }
}
