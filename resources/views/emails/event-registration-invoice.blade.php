@php
    $payerName = $transaction->payer_name ?: 'Attendee';
@endphp

<p>Hello {{ $payerName }},</p>

<p>Your payment for <strong>{{ $eventTitle }}</strong> has been confirmed.</p>

<p>
    Transaction Code: <strong>{{ $transaction->transaction_code }}</strong><br>
    Reference Code: <strong>{{ $transaction->reference_code }}</strong><br>
    Payment Status: <strong>{{ ucfirst($transaction->status) }}</strong>
</p>

<p>Your event ticket invoice is attached to this email. Please present the digital or printed ticket at registration.</p>

<p>Thank you.</p>
