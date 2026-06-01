@php
    $firstRegistration = $registrations->first();
    $event = $firstRegistration?->event;
    $payerName = $transaction->payer_name ?: 'Attendee';
    $bookingId = $transaction->reference_code ?: $transaction->transaction_code;
    $quantity = $registrations->count();
    $totalAmount = (float) $transaction->total_amount;
    $paymentMethod = $transaction->payment_mode ?: 'N/A';
    $paymentStatus = $totalAmount <= 0 ? 'Free' : ucfirst(str_replace('_', ' ', $transaction->status ?: 'paid'));
    $eventDate = $event?->start_date
        ? \Carbon\Carbon::parse($event->start_date)->format('F d, Y')
        : optional($transaction->created_at)->format('F d, Y');
    $formattedTotal = $totalAmount <= 0 ? '0.00' : '&#8369;' . number_format($totalAmount, 2);
@endphp

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Booking Invoice</title>
</head>

<body style="margin: 0; padding: 0; background: #eef1f5; color: #182233; font-family: Arial, Helvetica, sans-serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0"
        style="border-collapse: collapse; background: #eef1f5;">
        <tr>
            <td align="center" style="padding: 8px 12px 0;">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0"
                    style="border-collapse: collapse; width: 100%; max-width: 680px;">
                    <tr>
                        <td style="background: #1f2938; padding: 26px 40px 28px;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0"
                                style="border-collapse: collapse;">
                                <tr>
                                    <td align="left" style="vertical-align: top;">
                                        <div
                                            style="display: inline-block; color: #101827; font-size: 15px; font-weight: 700; line-height: 1; padding: 2px 4px;">
                                            MFC Portal
                                        </div>
                                    </td>
                                    <td align="right"
                                        style="color: #ffffff; font-size: 11px; line-height: 1.2; vertical-align: top;">
                                        Booking Invoice
                                    </td>
                                </tr>
                            </table>
                            <div style="color: #ffffff; font-size: 13px; font-weight: 700; margin-top: 16px;">
                                {{ $eventTitle }}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="background: #ffffff; border-radius: 0 0 8px 8px; padding: 40px;">
                            <p style="margin: 0 0 22px; color: #293647; font-size: 12px; line-height: 1.6;">
                                Hello {{ $payerName }},
                            </p>

                            <p style="margin: 0 0 32px; color: #182233; font-size: 13px; line-height: 1.6;">
                                Your booking has been confirmed. Please review the invoice details below.
                            </p>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0"
                                style="border: 1px solid #cbd3dd; border-collapse: separate; border-radius: 7px; margin-bottom: 32px;">
                                <tr>
                                    <td
                                        style="border-bottom: 1px solid #cbd3dd; color: #111827; font-size: 12px; font-weight: 700; padding: 16px 20px;">
                                        Booking Summary
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 0 20px;">
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0"
                                            style="border-collapse: collapse;">
                                            <tr>
                                                <td
                                                    style="border-bottom: 1px solid #e5e9ee; color: #5b6878; font-size: 12px; padding: 15px 0; width: 42%;">
                                                    Customer
                                                </td>
                                                <td align="right"
                                                    style="border-bottom: 1px solid #e5e9ee; color: #111827; font-size: 12px; font-weight: 700; padding: 15px 0;">
                                                    {{ $payerName }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td
                                                    style="border-bottom: 1px solid #e5e9ee; color: #5b6878; font-size: 12px; padding: 15px 0;">
                                                    Booking ID
                                                </td>
                                                <td align="right"
                                                    style="border-bottom: 1px solid #e5e9ee; color: #111827; font-size: 12px; font-weight: 700; padding: 15px 0;">
                                                    {{ $bookingId }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td
                                                    style="border-bottom: 1px solid #e5e9ee; color: #5b6878; font-size: 12px; padding: 15px 0;">
                                                    Event
                                                </td>
                                                <td align="right"
                                                    style="border-bottom: 1px solid #e5e9ee; color: #111827; font-size: 12px; font-weight: 700; line-height: 1.4; padding: 15px 0;">
                                                    {{ $eventTitle }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td
                                                    style="border-bottom: 1px solid #e5e9ee; color: #5b6878; font-size: 12px; padding: 15px 0;">
                                                    Payment Method
                                                </td>
                                                <td align="right"
                                                    style="border-bottom: 1px solid #e5e9ee; color: #111827; font-size: 12px; font-weight: 700; padding: 15px 0;">
                                                    {{ $paymentMethod }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td
                                                    style="border-bottom: 1px solid #e5e9ee; color: #5b6878; font-size: 12px; padding: 15px 0;">
                                                    Payment Status
                                                </td>
                                                <td align="right"
                                                    style="border-bottom: 1px solid #e5e9ee; color: #111827; font-size: 12px; font-weight: 700; padding: 15px 0;">
                                                    {{ $paymentStatus }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td
                                                    style="border-bottom: 1px solid #e5e9ee; color: #5b6878; font-size: 12px; padding: 15px 0;">
                                                    Quantity
                                                </td>
                                                <td align="right"
                                                    style="border-bottom: 1px solid #e5e9ee; color: #111827; font-size: 12px; font-weight: 700; padding: 15px 0;">
                                                    {{ $quantity }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td
                                                    style="border-bottom: 1px solid #e5e9ee; color: #5b6878; font-size: 12px; padding: 15px 0;">
                                                    Total Amount
                                                </td>
                                                <td align="right"
                                                    style="border-bottom: 1px solid #e5e9ee; color: #111827; font-size: 12px; font-weight: 700; padding: 15px 0;">
                                                    {!! $formattedTotal !!}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="color: #5b6878; font-size: 12px; padding: 15px 0;">
                                                    Event Date
                                                </td>
                                                <td align="right"
                                                    style="color: #111827; font-size: 12px; font-weight: 700; padding: 15px 0;">
                                                    {{ $eventDate ?: 'N/A' }}
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0"
                                style="border: 1px solid #cbd3dd; border-collapse: separate; border-radius: 7px; margin-bottom: 32px;">
                                <tr>
                                    <td
                                        style="border-bottom: 1px solid #cbd3dd; color: #111827; font-size: 12px; font-weight: 700; padding: 16px 20px;">
                                        Message
                                    </td>
                                </tr>
                                <tr>
                                    <td
                                        style="color: #182233; font-size: 12px; line-height: 1.7; padding: 36px 20px 34px;">
                                        <p style="margin: 0 0 18px;">Hi {{ $payerName }},</p>

                                        <p style="margin: 0 0 18px;">
                                            Thank you for purchasing your ticket at
                                            <span style=" color: #101827; font-weight: 700;">MFC
                                                Portal</span>.
                                        </p>

                                        <p style="margin: 0 0 6px;">
                                            Booking Id: {{ $bookingId }}
                                        </p>
                                        <p style="margin: 0 0 18px;">
                                            Event: {{ $eventTitle }}
                                        </p>

                                        <p style="margin: 0 0 34px;">
                                            Also, we have attached a copy of your ticket in this mail.
                                        </p>

                                        <p style="margin: 0 0 6px;">Best regards,</p>
                                        <p style="margin: 0;">
                                            <span style=" color: #101827; font-weight: 700;">MFC
                                                Portal</span>
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0"
                                style="border: 1px solid #cbd3dd; border-collapse: separate; border-radius: 7px;">
                                <tr>
                                    <td style="color: #182233; font-size: 12px; line-height: 1.6; padding: 18px 20px;">
                                        <div style="color: #111827; font-weight: 700; margin-bottom: 6px;">
                                            Attachment Included
                                        </div>
                                        <div>Your invoice PDF is attached to this email for your records.</div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td align="center"
                            style="color: #5b6878; font-size: 10px; line-height: 1.5; padding: 28px 16px;">
                            This is an automated transactional email from
                            <span style=" color: #101827; font-weight: 700;">MFC Portal</span>.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
