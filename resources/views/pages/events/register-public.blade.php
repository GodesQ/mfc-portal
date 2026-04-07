<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $event->title }} Registration | MFC Events</title>
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
            --gold-soft: #ffe389;
            --sky: #dce7fb;
            --surface: #ffffff;
            --canvas: #f5f7fc;
            --border: #dce3f0;
            --text: #21324f;
            --muted: #6d7b95;
            --success: #1d7a4d;
            --radius-lg: 24px;
            --radius-md: 18px;
            --radius-sm: 12px;
            --shadow: 0 20px 60px rgba(30, 45, 78, 0.12);
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
            color: var(--text);
            background:
                radial-gradient(circle at top left, rgba(245, 197, 24, 0.2), transparent 28%),
                linear-gradient(180deg, #eef3fb 0%, var(--canvas) 45%, #f8fafd 100%);
        }

        a { color: inherit; }

        .page-shell {
            max-width: 1100px;
            margin: 0 auto;
            padding: 32px 20px 56px;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            margin-bottom: 32px;
        }

        .brand-link,
        .back-link {
            text-decoration: none;
            font-weight: 700;
        }

        .brand-link {
            color: var(--navy);
            letter-spacing: -0.02em;
        }

        .back-link {
            color: var(--navy-soft);
            font-size: 14px;
        }

        .hero {
            display: grid;
            grid-template-columns: minmax(0, 1.4fr) minmax(300px, 0.9fr);
            gap: 24px;
            align-items: stretch;
        }

        .hero-card,
        .summary-card,
        .choice-card {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(220, 227, 240, 0.9);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow);
        }

        .hero-card {
            padding: 32px;
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 14px;
            border-radius: 999px;
            background: var(--sky);
            color: var(--navy);
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        h1 {
            margin: 18px 0 12px;
            font-size: clamp(2rem, 4vw, 3.4rem);
            line-height: 1.02;
            letter-spacing: -0.05em;
        }

        .hero-copy {
            margin: 0 0 24px;
            max-width: 48rem;
            color: var(--muted);
            font-size: 16px;
            line-height: 1.7;
        }

        .choice-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
        }

        .choice-card {
            padding: 22px;
        }

        .choice-title {
            margin: 0 0 10px;
            font-size: 22px;
            font-weight: 800;
            letter-spacing: -0.03em;
        }

        .choice-copy {
            margin: 0 0 22px;
            color: var(--muted);
            line-height: 1.65;
            min-height: 78px;
        }

        .choice-points {
            margin: 0 0 22px;
            padding: 0;
            list-style: none;
            color: var(--text);
        }

        .choice-points li {
            margin-bottom: 10px;
            padding-left: 26px;
            position: relative;
        }

        .choice-points li::before {
            content: "•";
            position: absolute;
            left: 8px;
            color: var(--success);
            font-weight: 800;
        }

        .btn-primary,
        .btn-secondary {
            display: inline-flex;
            width: 100%;
            justify-content: center;
            align-items: center;
            gap: 10px;
            padding: 15px 20px;
            border-radius: var(--radius-sm);
            text-decoration: none;
            font-weight: 800;
            transition: transform 0.18s ease, box-shadow 0.18s ease, background 0.18s ease;
        }

        .btn-primary {
            background: var(--navy);
            color: #fff;
            box-shadow: 0 14px 34px rgba(30, 45, 78, 0.2);
        }

        .btn-secondary {
            background: linear-gradient(135deg, var(--gold) 0%, var(--gold-soft) 100%);
            color: var(--navy);
            box-shadow: 0 14px 34px rgba(245, 197, 24, 0.22);
        }

        .btn-primary:hover,
        .btn-secondary:hover {
            transform: translateY(-2px);
        }

        .summary-card {
            padding: 26px;
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        .poster {
            width: 100%;
            aspect-ratio: 4 / 3;
            object-fit: cover;
            border-radius: var(--radius-md);
            background: linear-gradient(135deg, #dae5f7, #f8fbff);
        }

        .summary-title {
            margin: 0;
            font-size: 24px;
            font-weight: 800;
            letter-spacing: -0.03em;
        }

        .summary-meta {
            display: grid;
            gap: 14px;
        }

        .meta-row {
            display: grid;
            grid-template-columns: 24px 1fr;
            gap: 12px;
            color: var(--text);
        }

        .meta-row i {
            color: var(--navy-soft);
            font-size: 18px;
            margin-top: 2px;
        }

        .meta-label {
            display: block;
            color: var(--muted);
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: 2px;
        }

        .fee-panel {
            padding: 18px;
            border-radius: var(--radius-md);
            background: linear-gradient(135deg, rgba(30, 45, 78, 0.96), rgba(49, 69, 111, 0.96));
            color: #fff;
        }

        .fee-panel small {
            display: block;
            margin-bottom: 8px;
            opacity: 0.7;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .fee-amount {
            font-size: 34px;
            font-weight: 800;
            letter-spacing: -0.05em;
        }

        .phase-note {
            margin: 0;
            padding: 14px 16px;
            border-radius: var(--radius-sm);
            background: #fff9e3;
            border: 1px solid #f4dd7f;
            color: #735d00;
            line-height: 1.6;
        }

        @media (max-width: 900px) {
            .hero {
                grid-template-columns: 1fr;
            }

            .choice-grid {
                grid-template-columns: 1fr;
            }

            .hero-card,
            .summary-card,
            .choice-card {
                padding: 22px;
            }
        }
    </style>
</head>

<body>
    <div class="page-shell">
        <div class="topbar">
            <a href="{{ route('events.show', ['identifier' => $event->title]) }}" class="back-link">
                <i class="ri-arrow-left-line"></i> Back to event details
            </a>
            <a href="{{ route('events.show', ['identifier' => $event->title]) }}" class="brand-link">MFC Events</a>
        </div>

        <section class="hero">
            <div class="hero-card">
                <div class="eyebrow">
                    <i class="ri-ticket-2-line"></i>
                    Public Event Registration
                </div>

                <h1>Choose how you want to continue for {{ $event->title }}.</h1>
                <p class="hero-copy">
                    Members can sign in and use the regular event registration form. Guests can continue without creating an account and will complete registration on the public form.
                </p>

                <div class="choice-grid">
                    <article class="choice-card">
                        <h2 class="choice-title">Register as Member</h2>
                        <p class="choice-copy">
                            Continue with your member account so your registration stays linked to your existing profile and MFC ID.
                        </p>
                        <ul class="choice-points">
                            <li>Sign in first if you are not logged in yet.</li>
                            <li>After login, you will return to this event automatically.</li>
                            <li>Use the existing member registration flow.</li>
                        </ul>
                        <a href="{{ route('events.register.member', ['event' => $event->id]) }}" class="btn-primary">
                            <i class="ri-login-box-line"></i>
                            Register as Member
                        </a>
                    </article>

                    <article class="choice-card">
                        <h2 class="choice-title">Register as Guest</h2>
                        <p class="choice-copy">
                            Continue without logging in. Your registration will use the public guest form for payer and attendee details.
                        </p>
                        <ul class="choice-points">
                            <li>No member account is required.</li>
                            <li>The public form collects payer and attendee details for checkout.</li>
                            <li>Your registration continues directly to the Maya payment flow.</li>
                        </ul>
                        <a href="{{ route('events.register.guest', ['event' => $event->id]) }}" class="btn-secondary">
                            <i class="ri-user-add-line"></i>
                            Register as Guest
                        </a>
                    </article>
                </div>
            </div>

            <aside class="summary-card">
                @if ($event->poster)
                    <img src="{{ URL::asset('uploads/events/' . $event->poster) }}" alt="{{ $event->title }}" class="poster">
                @else
                    <div class="poster"></div>
                @endif

                <div>
                    <p class="meta-label">Event Summary</p>
                    <h2 class="summary-title">{{ $event->title }}</h2>
                </div>

                <div class="summary-meta">
                    <div class="meta-row">
                        <i class="ri-calendar-event-line"></i>
                        <div>
                            <span class="meta-label">Date</span>
                            <div>
                                {{ Carbon::parse($event->start_date)->format('F d, Y') }}
                                @if ($event->end_date && $event->end_date !== $event->start_date)
                                    to {{ Carbon::parse($event->end_date)->format('F d, Y') }}
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="meta-row">
                        <i class="ri-time-line"></i>
                        <div>
                            <span class="meta-label">Time</span>
                            <div>{{ Carbon::parse($event->time)->format('h:i A') }}</div>
                        </div>
                    </div>

                    <div class="meta-row">
                        <i class="ri-map-pin-line"></i>
                        <div>
                            <span class="meta-label">Location</span>
                            <div>{{ $event->location }}</div>
                        </div>
                    </div>
                </div>

                <div class="fee-panel">
                    <small>Registration Fee</small>
                    <div class="fee-amount">Php {{ number_format($event->reg_fee, 2) }}</div>
                </div>

                <p class="phase-note">
                    Members can sign in for the dashboard-backed flow, while guests can now complete the public registration form and continue to payment.
                </p>
            </aside>
        </section>
    </div>
</body>

</html>
