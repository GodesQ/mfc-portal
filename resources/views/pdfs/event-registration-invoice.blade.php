<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Event Registration Invoice</title>
    <style>
        @page {
            margin: 18px;
        }

        body {
            color: #111827;
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            line-height: 1.35;
            margin: 0;
        }

        .ticket-page {
            page-break-after: always;
        }

        .ticket-page.last-page {
            page-break-after: auto;
        }

        .ticket-frame,
        .instructions {
            border: 2px solid #ff2d6f;
        }

        .ticket-frame {
            min-height: 325px;
            padding: 0;
        }

        .ticket-table {
            border-collapse: collapse;
            width: 100%;
        }

        .poster-cell {
            background: #0f172a;
            height: 325px;
            text-align: center;
            vertical-align: middle;
            width: 34%;
        }

        .poster-cell img {
            display: block;
            height: 325px;
            object-fit: cover;
            width: 100%;
        }

        .poster-placeholder,
        .logo-placeholder,
        .qr-placeholder {
            color: #94a3b8;
            padding: 12px;
            text-align: center;
        }

        .details-cell {
            padding: 18px 20px 14px;
            vertical-align: top;
            width: 48%;
        }

        .side-cell {
            padding: 18px 16px 14px 0;
            text-align: center;
            vertical-align: top;
            width: 18%;
        }

        h1 {
            font-size: 17px;
            line-height: 1.25;
            margin: 0 0 8px;
        }

        .location {
            border-top: 1px solid #d1d5db;
            font-weight: bold;
            margin-top: 8px;
            padding-top: 8px;
        }

        .date {
            border-bottom: 1px solid #d1d5db;
            margin-bottom: 14px;
            padding: 8px 0 10px;
        }

        .ticket-name {
            border-bottom: 1px solid #d1d5db;
            font-size: 15px;
            margin-bottom: 10px;
            padding-bottom: 9px;
            text-transform: uppercase;
        }

        .meta-table {
            border-collapse: collapse;
            width: 100%;
        }

        .meta-table td {
            border-bottom: 1px solid #e5e7eb;
            padding: 7px 10px 7px 0;
            vertical-align: top;
            width: 33.333%;
        }

        .label {
            color: #9ca3af;
            display: block;
            font-size: 9px;
            font-weight: bold;
            letter-spacing: .04em;
            text-transform: uppercase;
        }

        .value {
            color: #111827;
            display: block;
            font-size: 11px;
            padding-top: 3px;
        }

        .qr-box img {
            height: 112px;
            width: 112px;
        }

        .attendee-name {
            font-size: 11px;
            font-weight: bold;
            margin: 10px 0 8px;
        }

        .logo-box {
            margin: 0 auto;
            max-width: 100px;
        }

        .logo-box img {
            max-height: 72px;
            max-width: 100px;
        }

        .instructions {
            margin-top: 8px;
            min-height: 120px;
            padding: 13px 15px;
        }

        .instructions p {
            margin: 0 0 8px;
        }
    </style>
</head>

<body>
    @foreach ($registrations as $ticket)
        @php
            $event = $ticket['event'];
            $eventDate = $event?->start_date ? \Carbon\Carbon::parse($event->start_date)->format('M d, Y') : 'N/A';
            $eventTime = $event?->time ?: 'N/A';
            $instructions =
                $ticket['ticket_instructions'] ?:
                '<p>Please present your digital or printed confirmation at the registration desk.</p>';
        @endphp

        <div class="ticket-page @if ($loop->last) last-page @endif">
            <div class="ticket-frame">
                <table class="ticket-table">
                    <tr>
                        <td class="poster-cell">
                            @if ($ticket['ticket_image_data_uri'])
                                <img src="{{ $ticket['ticket_image_data_uri'] }}"
                                    style="object-fit: cover; object-position: center;" alt="Ticket image">
                            @else
                                <div class="poster-placeholder">Ticket Image</div>
                            @endif
                        </td>
                        <td class="details-cell">
                            <h1>{{ $event?->title ?: 'Event Registration' }}</h1>
                            <div class="location">{{ $event?->location ?: 'N/A' }}</div>
                            <div class="date">{{ $eventDate }} {{ $eventTime }}</div>

                            <div class="ticket-name">{{ $ticket['ticket_name'] }}</div>

                            <table class="meta-table">
                                <tr>
                                    <td>
                                        <span class="label">Booking Date</span>
                                        <span class="value">{{ $ticket['booking_date'] }}</span>
                                    </td>
                                    <td>
                                        <span class="label">Seat Number</span>
                                        <span class="value">N/A</span>
                                    </td>
                                    <td>
                                        <span class="label">Booking ID</span>
                                        <span class="value">{{ $ticket['booking_id'] }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="label">Quantity</span>
                                        <span class="value">1</span>
                                    </td>
                                    <td>
                                        <span class="label">Early Bird</span>
                                        <span
                                            class="value">{{ number_format($ticket['early_bird_discount'], 2) }}</span>
                                    </td>
                                    <td>
                                        <span class="label">Coupon</span>
                                        <span class="value">0.00</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="label">Ticket Price</span>
                                        <span class="value">{{ number_format($ticket['ticket_price'], 2) }}</span>
                                    </td>
                                    <td>
                                        <span class="label">Sub Amount</span>
                                        <span class="value">{{ number_format($ticket['ticket_price'], 2) }}</span>
                                    </td>
                                    <td>
                                        <span class="label">VAT (12%)</span>
                                        <span class="value">0.00</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="label">Total Paid</span>
                                        <span class="value">{{ number_format($ticket['total_paid'], 2) }}</span>
                                    </td>
                                    <td>
                                        <span class="label">Payment Method</span>
                                        <span class="value">Maya</span>
                                    </td>
                                    <td>
                                        <span class="label">Payment Status</span>
                                        <span class="value">Paid</span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td class="side-cell">
                            <div class="qr-box">
                                @if ($ticket['qr_code_data_uri'])
                                    <img src="{{ $ticket['qr_code_data_uri'] }}" alt="Registration QR code">
                                @else
                                    <div class="qr-placeholder">{{ $ticket['booking_id'] }}</div>
                                @endif
                            </div>
                            <div class="attendee-name">{{ $ticket['attendee_name'] }}</div>
                            <div class="logo-box">
                                @if ($ticket['ticket_logo_data_uri'])
                                    <img src="{{ $ticket['ticket_logo_data_uri'] }}" alt="Ticket logo">
                                @else
                                    <div class="logo-placeholder">Ticket Logo</div>
                                @endif
                            </div>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="instructions">
                {!! $instructions !!}
            </div>
        </div>
    @endforeach
</body>

</html>
