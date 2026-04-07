<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $event->title }} Guest Registration | MFC Events</title>
    <link rel="shortcut icon" href="{{ URL::asset('build/images/favicon.ico') }}">
    @include('layouts.head-css')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --navy: #1e2d4e;
            --navy-soft: #31456f;
            --gold: #f5c518;
            --gold-soft: #ffe28b;
            --surface: #ffffff;
            --canvas: #f4f7fc;
            --border: #dbe3f0;
            --text: #20314b;
            --muted: #6d7b95;
            --danger: #c53a4d;
            --shadow: 0 20px 60px rgba(30, 45, 78, 0.12);
            --radius-lg: 24px;
            --radius-md: 18px;
            --radius-sm: 12px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
            color: var(--text);
            background:
                radial-gradient(circle at top right, rgba(245, 197, 24, 0.18), transparent 24%),
                linear-gradient(180deg, #eef3fb 0%, var(--canvas) 44%, #f7f9fd 100%);
        }

        a {
            color: inherit;
        }

        .page-shell {
            max-width: 1200px;
            margin: 0 auto;
            padding: 28px 20px 56px;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 14px;
            margin-bottom: 24px;
        }

        .topbar a {
            text-decoration: none;
            font-weight: 700;
            color: var(--navy);
        }

        .hero {
            display: grid;
            grid-template-columns: minmax(0, 1.45fr) minmax(320px, 0.95fr);
            gap: 24px;
            align-items: start;
        }

        .card {
            background: rgba(255, 255, 255, 0.94);
            border: 1px solid rgba(219, 227, 240, 0.95);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow);
        }

        .form-card {
            padding: 28px;
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 14px;
            border-radius: 999px;
            background: rgba(30, 45, 78, 0.08);
            color: var(--navy);
            font-size: 13px;
            font-weight: 800;
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }

        h1 {
            margin: 16px 0 10px;
            font-size: clamp(2rem, 4vw, 2.45rem);
            line-height: 1.02;
            letter-spacing: -0.05em;
        }

        .intro-copy {
            margin: 0 0 24px;
            color: var(--muted);
            line-height: 1.75;
            max-width: 46rem;
        }

        .alert {
            margin-bottom: 20px;
            padding: 14px 16px;
            border-radius: var(--radius-sm);
            border: 1px solid rgba(197, 58, 77, 0.18);
            background: rgba(197, 58, 77, 0.08);
            color: #8f2332;
        }

        .section {
            margin-top: 26px;
            padding-top: 24px;
            border-top: 1px solid var(--border);
            padding: 10px 0 0 0 !important;
        }

        .section:first-of-type {
            margin-top: 0;
            padding-top: 0;
            border-top: 0;
        }

        .section h2 {
            margin: 0 0 8px;
            font-size: 22px;
            letter-spacing: -0.03em;
        }

        .section p {
            margin: 0 0 20px;
            color: var(--muted);
            line-height: 1.65;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
        }

        .field {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .field.full {
            grid-column: 1 / -1;
        }

        .field label {
            font-weight: 700;
            font-size: 14px;
        }

        .field input,
        .field select,
        .field textarea {
            width: 100%;
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 14px 15px;
            font: inherit;
            color: var(--text);
            background: #fff;
            transition: border-color 0.18s ease, box-shadow 0.18s ease;
        }

        .field textarea {
            min-height: 112px;
            resize: vertical;
        }

        .field input:focus,
        .field select:focus,
        .field textarea:focus {
            outline: none;
            border-color: #9ab0e5;
            box-shadow: 0 0 0 4px rgba(122, 163, 204, 0.16);
        }

        .field .error {
            color: var(--danger);
            font-size: 13px;
            font-weight: 600;
        }

        .attendee-card {
            padding: 18px;
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.98), rgba(246, 249, 255, 0.95));
        }

        .inline-note {
            margin: 12px 0 20px 0;
            padding: 12px 14px;
            border-radius: var(--radius-sm);
            background: #fff8df;
            color: #786000;
            border: 1px solid #f0db8a;
            font-size: 14px;
            line-height: 1.6;
        }

        .submit-row {
            display: flex;
            flex-wrap: wrap;
            gap: 14px;
            align-items: center;
            justify-content: space-between;
            margin-top: 28px;
        }

        .submit-copy {
            color: var(--muted);
            line-height: 1.7;
            max-width: 30rem;
        }

        .btn-submit {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            border: 0;
            border-radius: 16px;
            padding: 16px 22px;
            background: linear-gradient(135deg, var(--navy) 0%, var(--navy-soft) 100%);
            color: #fff;
            font: inherit;
            font-weight: 800;
            cursor: pointer;
            box-shadow: 0 16px 34px rgba(30, 45, 78, 0.2);
        }

        .sidebar {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .summary-card,
        .event-card {
            padding: 24px;
        }

        .summary-list,
        .event-meta {
            display: grid;
            gap: 14px;
        }

        .summary-row,
        .meta-row {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            align-items: flex-start;
        }

        .summary-row strong,
        .meta-row strong {
            font-size: 15px;
        }

        .summary-row span,
        .meta-row span {
            color: var(--muted);
            text-align: right;
        }

        .summary-total {
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px solid var(--border);
            font-size: 18px;
            font-weight: 800;
        }

        .poster {
            width: 100%;
            aspect-ratio: 4 / 3;
            object-fit: cover;
            border-radius: var(--radius-md);
            background: linear-gradient(135deg, #dae5f7, #f8fbff);
            margin-bottom: 18px;
        }

        .fee-chip {
            display: inline-flex;
            align-items: center;
            padding: 10px 14px;
            border-radius: 999px;
            background: rgba(245, 197, 24, 0.18);
            color: #816500;
            font-weight: 800;
            margin-bottom: 16px;
        }

        .event-title {
            margin: 0 0 14px;
            font-size: 26px;
            line-height: 1.12;
            letter-spacing: -0.04em;
        }

        .event-description {
            color: var(--muted);
            line-height: 1.75;
        }

        @media (max-width: 980px) {
            .hero {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 700px) {
            .form-grid {
                grid-template-columns: 1fr;
            }

            .form-card,
            .summary-card,
            .event-card {
                padding: 22px;
            }

            .submit-row {
                align-items: stretch;
            }

            .btn-submit {
                width: 100%;
            }
        }
    </style>
</head>

<body>
    @php
        $tshirtSizes = ['XS', 'S', 'M', 'L', 'XL', '2XL', '3XL'];
        $areaOptions = [
            'ncr_north' => 'NCR - North',
            'ncr_south' => 'NCR - South',
            'ncr_east' => 'NCR - East',
            'ncr_central' => 'NCR - Central',
            'south_luzon' => 'South Luzon',
            'north_and_central_luzon' => 'North & Central Luzon',
            'visayas' => 'Visayas',
            'mindanao' => 'Mindanao',
            'international' => 'International',
            'baguio' => 'Baguio',
            'palawan' => 'Palawan',
            'batangas' => 'Batangas',
            'laguna' => 'Laguna',
            'pampanga' => 'Pampanga',
            'tarlac' => 'Tarlac',
            'other' => 'Other',
        ];
        $attendees = old('attendees', []);
    @endphp
    <div class="page-shell">
        <div class="topbar">
            <a href="{{ route('events.register.public', ['event' => $event->id]) }}">
                <i class="ri-arrow-left-line"></i> Back to registration options
            </a>
            <a href="{{ route('events.show', ['identifier' => $event->title]) }}">View event details</a>
        </div>

        <div class="hero">
            <main class="card form-card">
                <div class="eyebrow">
                    <i class="ri-user-add-line"></i>
                    Guest Registration
                </div>
                <h1>Registration for {{ $event->title }}.</h1>

                @if ($errors->any())
                    <div class="alert">
                        Please review the highlighted fields and try again.
                    </div>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif

                <form method="POST" action="{{ route('events.register.guest.post', ['event' => $event->id]) }}">
                    @csrf

                    <section class="section">
                        <h2>Primary Attendee and Payer</h2>
                        <p>These details are used as the payer snapshot for the transaction receipt, checkout request,
                            and primary attendee record.</p>
                        <div class="inline-note">
                            This person will be saved as the payer and the primary attendee for this event registration.
                        </div>

                        <div class="form-grid">
                            <div class="field">
                                <label for="payer_first_name">First Name</label>
                                <input id="payer_first_name" name="payer_first_name" type="text"
                                    value="{{ old('payer_first_name') }}">
                                @error('payer_first_name')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="field">
                                <label for="payer_last_name">Last Name</label>
                                <input id="payer_last_name" name="payer_last_name" type="text"
                                    value="{{ old('payer_last_name') }}">
                                @error('payer_last_name')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="field">
                                <label for="payer_email">Email</label>
                                <input id="payer_email" name="payer_email" type="email"
                                    value="{{ old('payer_email') }}">
                                @error('payer_email')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="field">
                                <label for="payer_contact_number">Contact Number</label>
                                <input id="payer_contact_number" name="payer_contact_number" type="text"
                                    value="{{ old('payer_contact_number') }}">
                                @error('payer_contact_number')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="field">
                                <label for="payer_tshirt_size">T-shirt Size</label>
                                <select id="payer_tshirt_size" name="payer_tshirt_size">
                                    <option value="">Select T-shirt Size</option>
                                    @foreach ($tshirtSizes as $size)
                                        <option value="{{ $size }}"
                                            {{ old('payer_tshirt_size') === $size ? 'selected' : '' }}>
                                            {{ $size }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('payer_tshirt_size')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="field">
                                <label for="payer_mfc_section">MFC Section</label>
                                <select id="payer_mfc_section" name="payer_mfc_section">
                                    <option value="">Select MFC Section</option>
                                    @foreach ($sections as $section)
                                        <option value="{{ $section->name }}"
                                            {{ old('payer_mfc_section') === $section->name ? 'selected' : '' }}>
                                            {{ ucfirst($section->name) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('payer_mfc_section')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="field">
                                <label for="payer_area">Area</label>
                                <select id="payer_area" name="payer_area">
                                    <option value="">Select Area</option>
                                    @foreach ($areaOptions as $value => $label)
                                        <option value="{{ $value }}"
                                            {{ old('payer_area') === $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('payer_area')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="field full">
                                <label for="payer_address">Address</label>
                                <textarea id="payer_address" name="payer_address">{{ old('payer_address') }}</textarea>
                                @error('payer_address')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </section>

                    <section class="section">
                        <h2>Additional Attendees</h2>
                        <p>Add attendees here only if you are registering more people aside from the primary attendee
                            above.</p>

                        <div id="attendees-list">
                            @foreach ($attendees as $index => $attendee)
                                <div class="attendee-card" data-attendee-card>
                                    <div class="form-grid">
                                        <div class="field full">
                                            <label>Attendee Type</label>
                                            <input type="hidden" value="Normal User" readonly data-attendee-role>
                                        </div>

                                        <div class="field">
                                            <label for="attendee_{{ $index }}_first_name">First Name</label>
                                            <input id="attendee_{{ $index }}_first_name"
                                                name="attendees[{{ $index }}][first_name]" type="text"
                                                value="{{ old('attendees.' . $index . '.first_name', $attendee['first_name'] ?? '') }}">
                                            @error('attendees.' . $index . '.first_name')
                                                <span class="error">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="field">
                                            <label for="attendee_{{ $index }}_last_name">Last Name</label>
                                            <input id="attendee_{{ $index }}_last_name"
                                                name="attendees[{{ $index }}][last_name]" type="text"
                                                value="{{ old('attendees.' . $index . '.last_name', $attendee['last_name'] ?? '') }}">
                                            @error('attendees.' . $index . '.last_name')
                                                <span class="error">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="field">
                                            <label for="attendee_{{ $index }}_email">Email</label>
                                            <input id="attendee_{{ $index }}_email"
                                                name="attendees[{{ $index }}][email]" type="email"
                                                value="{{ old('attendees.' . $index . '.email', $attendee['email'] ?? '') }}">
                                            @error('attendees.' . $index . '.email')
                                                <span class="error">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="field">
                                            <label for="attendee_{{ $index }}_contact_number">Contact
                                                Number</label>
                                            <input id="attendee_{{ $index }}_contact_number"
                                                name="attendees[{{ $index }}][contact_number]" type="text"
                                                value="{{ old('attendees.' . $index . '.contact_number', $attendee['contact_number'] ?? '') }}">
                                            @error('attendees.' . $index . '.contact_number')
                                                <span class="error">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="field">
                                            <label for="attendee_{{ $index }}_tshirt_size">T-shirt Size</label>
                                            <select id="attendee_{{ $index }}_tshirt_size"
                                                name="attendees[{{ $index }}][tshirt_size]">
                                                <option value="">Select T-shirt Size</option>
                                                @foreach ($tshirtSizes as $size)
                                                    <option value="{{ $size }}"
                                                        {{ old('attendees.' . $index . '.tshirt_size', $attendee['tshirt_size'] ?? '') === $size ? 'selected' : '' }}>
                                                        {{ $size }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('attendees.' . $index . '.tshirt_size')
                                                <span class="error">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="field">
                                            <label for="attendee_{{ $index }}_mfc_section">MFC Section</label>
                                            <select id="attendee_{{ $index }}_mfc_section"
                                                name="attendees[{{ $index }}][mfc_section]">
                                                <option value="">Select MFC Section</option>
                                                @foreach ($sections as $section)
                                                    <option value="{{ $section->name }}"
                                                        {{ old('attendees.' . $index . '.mfc_section', $attendee['mfc_section'] ?? '') === $section->name ? 'selected' : '' }}>
                                                        {{ ucfirst($section->name) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('attendees.' . $index . '.mfc_section')
                                                <span class="error">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="field">
                                            <label for="attendee_{{ $index }}_area">Area</label>
                                            <select id="attendee_{{ $index }}_area"
                                                name="attendees[{{ $index }}][area]">
                                                <option value="">Select Area</option>
                                                @foreach ($areaOptions as $value => $label)
                                                    <option value="{{ $value }}"
                                                        {{ old('attendees.' . $index . '.area', $attendee['area'] ?? '') === $value ? 'selected' : '' }}>
                                                        {{ $label }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('attendees.' . $index . '.area')
                                                <span class="error">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="field full">
                                            <label for="attendee_{{ $index }}_address">Address</label>
                                            <textarea id="attendee_{{ $index }}_address" name="attendees[{{ $index }}][address]">{{ old('attendees.' . $index . '.address', $attendee['address'] ?? '') }}</textarea>
                                            @error('attendees.' . $index . '.address')
                                                <span class="error">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="submit-row" style="margin-top: 18px;">
                                        <div class="submit-copy">Normal attendees are charged the same registration and
                                            convenience fees.</div>
                                        <button type="button" class="btn-submit" data-remove-attendee
                                            style="padding: 12px 18px;">Remove Attendee</button>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @error('attendees')
                            <span class="error">{{ $message }}</span>
                        @enderror

                        <div class="inline-note">
                            The primary attendee is already covered above. Additional attendees will each receive a
                            dedicated event registration row.
                        </div>

                        <div class="submit-row">
                            <div class="submit-copy">
                                Click below only when you need to register another attendee in addition to the primary
                                attendee above.
                            </div>
                            <button type="button" class="btn-submit" id="add-attendee-btn">
                                <i class="ri-user-add-line"></i>
                                Add Attendee
                            </button>
                        </div>
                    </section>

                    <section class="section">
                        <h2>Donation</h2>
                        <p>Add an optional donation to the same checkout. Totals update live below.</p>

                        <div class="form-grid">
                            <div class="field">
                                <label for="donation">Donation Amount</label>
                                <input id="donation" name="donation" type="number" min="0" step="0.01"
                                    value="{{ old('donation', 0) }}">
                                @error('donation')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </section>

                    <div class="submit-row">
                        <div class="submit-copy">
                            Submitting this form creates the guest transaction, event registration, and attendee
                            snapshot before redirecting you to Maya checkout.
                        </div>
                        <button type="submit" class="btn-submit">
                            <i class="ri-secure-payment-line"></i>
                            Proceed to Payment
                        </button>
                    </div>
                </form>
            </main>

            <aside class="sidebar">
                <section class="card summary-card">
                    <h2 style="margin-top: 0;">Order Summary</h2>
                    <div class="summary-list">
                        <div class="summary-row">
                            <strong>Registration Fee</strong>
                            <span id="registration-fee">Php {{ number_format($event->reg_fee, 2) }}</span>
                        </div>
                        <div class="summary-row">
                            <strong>Convenience Fee</strong>
                            <span id="convenience-fee">Php 10.00</span>
                        </div>
                        <div class="summary-row">
                            <strong>Donation</strong>
                            <span id="donation-summary">Php {{ number_format((float) old('donation', 0), 2) }}</span>
                        </div>
                    </div>
                    <div class="summary-row summary-total">
                        <strong>Total Amount</strong>
                        <span id="total-amount">Php
                            {{ number_format($event->reg_fee + 10 + (float) old('donation', 0), 2) }}</span>
                    </div>
                </section>

                {{-- <section class="card event-card">
                    @if ($event->poster)
                        <img src="{{ URL::asset('uploads/events/' . $event->poster) }}" alt="{{ $event->title }}"
                            class="poster">
                    @else
                        <div class="poster"></div>
                    @endif

                    <div class="fee-chip">Registration Fee: Php {{ number_format($event->reg_fee, 2) }}</div>
                    <h2 class="event-title">{{ $event->title }}</h2>

                    <div class="event-meta">
                        <div class="meta-row">
                            <strong>Date</strong>
                            <span>
                                {{ Carbon::parse($event->start_date)->format('F d, Y') }}
                                @if ($event->end_date && $event->end_date !== $event->start_date)
                                    to {{ Carbon::parse($event->end_date)->format('F d, Y') }}
                                @endif
                            </span>
                        </div>
                        <div class="meta-row">
                            <strong>Time</strong>
                            <span>{{ Carbon::parse($event->time)->format('h:i A') }}</span>
                        </div>
                        <div class="meta-row">
                            <strong>Location</strong>
                            <span>{{ $event->location }}</span>
                        </div>
                    </div>
                    <hr>
                    <div class="event-description">
                        <h3>Description</h3>
                        {!! $event->description !!}
                    </div>
                </section> --}}
            </aside>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const donationInput = document.getElementById('donation');
            const donationSummary = document.getElementById('donation-summary');
            const totalAmount = document.getElementById('total-amount');
            const registrationFeeLabel = document.getElementById('registration-fee');
            const convenienceFeeLabel = document.getElementById('convenience-fee');
            const attendeesList = document.getElementById('attendees-list');
            const addAttendeeButton = document.getElementById('add-attendee-btn');
            const registrationFee = {{ json_encode((float) $event->reg_fee) }};
            const convenienceFee = 10;
            const tshirtSizes = @json($tshirtSizes);
            const sections = @json($sections->pluck('name')->values());
            const areaOptions = @json($areaOptions);

            const formatPhp = function(value) {
                return 'Php ' + Number(value).toLocaleString('en-PH', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            };

            const escapeHtml = function(value) {
                return String(value ?? '')
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');
            };

            const renderOptions = function(items, placeholder, selectedValue) {
                let output = '<option value="">' + placeholder + '</option>';

                Object.entries(items).forEach(function([value, label]) {
                    const selected = value === selectedValue ? ' selected' : '';
                    output += '<option value="' + escapeHtml(value) + '"' + selected + '>' + escapeHtml(
                        label) + '</option>';
                });

                return output;
            };

            const renderIndexedOptions = function(items, placeholder, selectedValue) {
                const formattedItems = {};

                items.forEach(function(item) {
                    formattedItems[item] = item.charAt(0).toUpperCase() + item.slice(1);
                });

                return renderOptions(formattedItems, placeholder, selectedValue);
            };

            const attendeeCount = function() {
                return attendeesList.querySelectorAll('[data-attendee-card]').length;
            };

            const buildAttendeeCard = function(index) {
                return `
                    <div class="attendee-card" data-attendee-card>
                        <div class="form-grid">
                            <div class="field full">
                                <label>Attendee Type</label>
                                <input type="hidden" value="Normal User" readonly data-attendee-role>
                            </div>
                            <div class="field">
                                <label for="attendee_${index}_first_name">First Name</label>
                                <input id="attendee_${index}_first_name" name="attendees[${index}][first_name]" type="text">
                            </div>
                            <div class="field">
                                <label for="attendee_${index}_last_name">Last Name</label>
                                <input id="attendee_${index}_last_name" name="attendees[${index}][last_name]" type="text">
                            </div>
                            <div class="field">
                                <label for="attendee_${index}_email">Email</label>
                                <input id="attendee_${index}_email" name="attendees[${index}][email]" type="email">
                            </div>
                            <div class="field">
                                <label for="attendee_${index}_contact_number">Contact Number</label>
                                <input id="attendee_${index}_contact_number" name="attendees[${index}][contact_number]" type="text">
                            </div>
                            <div class="field">
                                <label for="attendee_${index}_tshirt_size">T-shirt Size</label>
                                <select id="attendee_${index}_tshirt_size" name="attendees[${index}][tshirt_size]">${renderOptions(Object.fromEntries(tshirtSizes.map(function (size) { return [size, size]; })), 'Select T-shirt Size', '')}</select>
                            </div>
                            <div class="field">
                                <label for="attendee_${index}_mfc_section">MFC Section</label>
                                <select id="attendee_${index}_mfc_section" name="attendees[${index}][mfc_section]">${renderIndexedOptions(sections, 'Select MFC Section', '')}</select>
                            </div>
                            <div class="field">
                                <label for="attendee_${index}_area">Area</label>
                                <select id="attendee_${index}_area" name="attendees[${index}][area]">${renderOptions(areaOptions, 'Select Area', '')}</select>
                            </div>
                            <div class="field full">
                                <label for="attendee_${index}_address">Address</label>
                                <textarea id="attendee_${index}_address" name="attendees[${index}][address]"></textarea>
                            </div>
                        </div>
                        <div class="submit-row" style="margin-top: 18px;">
                            <div class="submit-copy">Normal attendees are charged the same registration and convenience fees.</div>
                            <button type="button" class="btn-submit" data-remove-attendee style="padding: 12px 18px;">Remove Attendee</button>
                        </div>
                    </div>
                `;
            };

            const updateSummary = function() {
                const donation = Math.max(parseFloat(donationInput.value || '0') || 0, 0);
                const count = attendeeCount() + 1;
                donationSummary.textContent = formatPhp(donation);
                registrationFeeLabel.textContent = formatPhp(registrationFee * count);
                convenienceFeeLabel.textContent = formatPhp(convenienceFee * count);
                totalAmount.textContent = formatPhp((registrationFee * count) + (convenienceFee * count) +
                    donation);
            };

            donationInput.addEventListener('input', updateSummary);

            addAttendeeButton.addEventListener('click', function() {
                attendeesList.insertAdjacentHTML('beforeend', buildAttendeeCard(attendeeCount()));
                updateSummary();
            });

            attendeesList.addEventListener('click', function(event) {
                const removeButton = event.target.closest('[data-remove-attendee]');

                if (!removeButton) {
                    return;
                }

                removeButton.closest('[data-attendee-card]').remove();
                updateSummary();
            });

            updateSummary();
        });
    </script>
</body>

</html>
