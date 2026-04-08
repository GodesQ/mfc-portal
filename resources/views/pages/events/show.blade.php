@extends('layouts.public.public-layout')

@push('head-styles')
    <style>
        /* ─── Navbar ───────────────────────────────────────── */
        .mfc-nav {
            position: sticky;
            top: 0;
            z-index: 100;
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            padding: 0 24px;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: var(--shadow-sm);
        }

        .mfc-nav__logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .mfc-nav__logo img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }

        .mfc-nav__brand {
            font-size: 15px;
            font-weight: 700;
            color: var(--navy);
            letter-spacing: -0.2px;
        }

        .mfc-nav__brand span {
            color: var(--blue);
        }

        .mfc-nav__actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* ─── Buttons ──────────────────────────────────────── */
        .btn-mfc-primary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--navy);
            color: #fff;
            border: none;
            border-radius: var(--radius-sm);
            padding: 9px 18px;
            font-size: 14px;
            font-weight: 500;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
        }

        .btn-mfc-primary:hover {
            background: var(--navy-light);
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(30, 45, 78, .25);
        }

        .btn-mfc-ghost {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: transparent;
            color: var(--navy);
            border: 1.5px solid var(--border);
            border-radius: var(--radius-sm);
            padding: 9px 18px;
            font-size: 14px;
            font-weight: 500;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            text-decoration: none;
            transition: border-color 0.2s, background 0.2s;
        }

        .btn-mfc-ghost:hover {
            border-color: var(--blue);
            background: rgba(59, 94, 166, .06);
            color: var(--blue);
        }

        .btn-register {
            width: 100%;
            justify-content: center;
            text-align: center;
            background: var(--gold);
            color: var(--navy);
            font-weight: 700;
            font-size: 15px;
            padding: 14px 20px;
            border-radius: var(--radius-md);
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            font-family: 'Inter', sans-serif;
            letter-spacing: 0.2px;
        }

        .btn-register:hover {
            background: var(--gold-dark);
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(245, 197, 24, .40);
        }

        .btn-register:active {
            transform: translateY(0);
        }

        /* ─── Page Layout ──────────────────────────────────── */
        .event-page {
            max-width: 1100px;
            margin: 0 auto;
            padding: 40px 24px 80px;
        }

        /* ─── Breadcrumb ───────────────────────────────────── */
        .breadcrumb-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 28px;
            font-size: 13px;
            color: var(--text-muted);
        }

        .breadcrumb-row a {
            color: var(--blue);
            text-decoration: none;
            font-weight: 500;
        }

        .breadcrumb-row a:hover {
            text-decoration: underline;
        }

        .breadcrumb-row .sep {
            color: var(--border);
        }

        /* ─── Two-column grid ──────────────────────────────── */
        .event-grid {
            display: grid;
            grid-template-columns: 1fr 340px;
            gap: 28px;
            align-items: start;
        }

        @media (max-width: 900px) {
            .event-grid {
                grid-template-columns: 1fr;
            }
        }

        /* ─── Cards ────────────────────────────────────────── */
        .card-clean {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            overflow: hidden;
            box-shadow: var(--shadow-md);
        }

        /* ─── Left: Event Content ──────────────────────────── */
        .event-header {
            padding: 32px 32px 24px;
            border-bottom: 1px solid var(--border);
        }

        .event-tags {
            display: flex;
            gap: 8px;
            margin-bottom: 14px;
            flex-wrap: wrap;
        }

        .tag {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .tag-navy {
            background: rgba(30, 45, 78, .08);
            color: var(--navy);
        }

        .tag-gold {
            background: rgba(245, 197, 24, .15);
            color: #a07500;
        }

        .event-title {
            font-size: 26px;
            font-weight: 700;
            color: var(--navy);
            line-height: 1.3;
            letter-spacing: -0.5px;
            margin-bottom: 0;
        }

        .event-body {
            padding: 28px 32px;
        }

        .section-label {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-bottom: 12px;
        }

        .event-description {
            font-size: 15px;
            line-height: 1.75;
            color: var(--text-body);
        }

        .event-description p {
            margin-bottom: 12px;
        }

        .event-poster-wrap {
            margin-top: 28px;
            border-radius: var(--radius-md);
            overflow: hidden;
            border: 1px solid var(--border);
        }

        .event-poster-wrap img {
            width: 100%;
            display: block;
            object-fit: cover;
        }

        /* ─── Right: Details Sidebar ───────────────────────── */
        .sidebar-stack {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .detail-card {
            padding: 24px;
        }

        .detail-card__header {
            font-size: 13px;
            font-weight: 700;
            color: var(--navy);
            margin-bottom: 18px;
            letter-spacing: 0.3px;
        }

        /* ─── Map ──────────────────────────────────────────── */
        #map {
            width: 100%;
            height: 200px;
            border-radius: var(--radius-md);
            overflow: hidden;
            border: 1px solid var(--border);
            margin-bottom: 20px;
        }

        /* ─── Detail Items ─────────────────────────────────── */
        .detail-list {
            display: flex;
            flex-direction: column;
            gap: 0;
        }

        .detail-item {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            padding: 14px 0;
            border-bottom: 1px solid var(--border);
        }

        .detail-item:last-child {
            border-bottom: none;
        }

        .detail-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: rgba(59, 94, 166, .08);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .detail-icon i {
            font-size: 16px;
            color: var(--blue);
        }

        .detail-content {
            flex: 1;
        }

        .detail-content__label {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            color: var(--text-muted);
            margin-bottom: 3px;
        }

        .detail-content__value {
            font-size: 14px;
            font-weight: 600;
            color: var(--navy);
            line-height: 1.4;
        }

        /* ─── Fee highlight ────────────────────────────────── */
        .fee-highlight {
            background: linear-gradient(135deg, var(--navy) 0%, var(--navy-soft) 100%);
            border-radius: var(--radius-md);
            padding: 18px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 18px 0;
        }

        .fee-label {
            font-size: 12px;
            font-weight: 600;
            color: rgba(255, 255, 255, .65);
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .fee-amount {
            font-size: 22px;
            font-weight: 800;
            color: var(--gold);
            letter-spacing: -0.5px;
        }

        .fee-icon {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, .1);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .fee-icon i {
            font-size: 18px;
            color: var(--gold);
        }

        /* ─── Register CTA card ────────────────────────────── */
        .register-card {
            padding: 24px;
            display: flex;
            flex-direction: column;
            align-items: center;
            background: linear-gradient(160deg, rgba(59, 94, 166, .04) 0%, rgba(245, 197, 24, .04) 100%);
        }

        .register-note {
            font-size: 12px;
            color: var(--text-muted);
            text-align: center;
            margin-top: 10px;
            line-height: 1.5;
        }

        /* ─── Social Media ─────────────────────────────────── */
        .social-card {
            padding: 20px 24px;
        }

        .social-links {
            display: flex;
            gap: 10px;
            margin-top: 12px;
        }

        .social-btn {
            width: 38px;
            height: 38px;
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all 0.2s;
            font-size: 16px;
        }

        .social-btn:hover {
            transform: translateY(-2px);
        }

        .social-btn--fb {
            background: #1877f2;
            color: #fff;
        }

        .social-btn--fb:hover {
            background: #0e5fc7;
            color: #fff;
        }

        .social-btn--ig {
            background: linear-gradient(45deg, #f09433, #e6683c, #dc2743, #cc2366, #bc1888);
            color: #fff;
        }

        .social-btn--ig:hover {
            color: #fff;
            opacity: .9;
        }

        .social-btn--yt {
            background: #ff0000;
            color: #fff;
        }

        .social-btn--yt:hover {
            background: #cc0000;
            color: #fff;
        }

        /* ─── Divider helper ───────────────────────────────── */
        .content-divider {
            margin: 24px 0;
            border: none;
            border-top: 1px solid var(--border);
        }

        /* ─── Accent bar on left card top ─────────────────── */
        .card-clean.has-accent {
            border-top: 3px solid var(--gold);
        }
    </style>
@endpush

@section('content')
    <!-- ── Navbar ──────────────────────────────────────────── -->
    <nav class="mfc-nav">
        <a href="#" class="mfc-nav__logo">
            <img src="{{ URL::asset('build/images/MFC-Logo.jpg') }}" alt="MFC Logo">
            <span class="mfc-nav__brand">MFC <span>Portal</span></span>
        </a>
        <div class="mfc-nav__actions">
            @if (auth()->user())
                <a href="{{ route('dashboards.index') }}" class="btn-mfc-primary">
                    <i class="ri-dashboard-line"></i> Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="btn-mfc-ghost">Login</a>
                <a href="{{ route('register') }}" class="btn-mfc-primary">Sign Up</a>
            @endif
        </div>
    </nav>

    <!-- ── Page ─────────────────────────────────────────────── -->
    <div class="event-page">

        <!-- Breadcrumb -->
        <div class="breadcrumb-row">
            <a href="#">Home</a>
            <span class="sep">›</span>
            <a href="#">Events</a>
            <span class="sep">›</span>
            <span>{{ $event->title }}</span>
        </div>

        <!-- Two-column grid -->
        <div class="event-grid">

            <!-- ── LEFT: Event Content ─────────────────────── -->
            <div class="card-clean has-accent">

                <!-- Header -->
                <div class="event-header">
                    <div class="event-tags">
                        <span class="tag tag-navy">
                            <i class="ri-global-line" style="font-size:10px;"></i> Worldwide
                        </span>
                        <span class="tag tag-gold">
                            <i class="ri-star-line" style="font-size:10px;"></i> Featured
                        </span>
                    </div>
                    <h1 class="event-title">{{ $event->title }}</h1>
                </div>

                <!-- Body -->
                <div class="event-body">
                    <div class="event-description">
                        <p class="section-label">About this event</p>
                        {!! $event->description !!}
                    </div>

                    @if ($event->poster)
                        <div class="event-poster-wrap">
                            <img src="{{ URL::asset('uploads/' . $event->poster) }}" alt="{{ $event->title }} Poster">
                        </div>
                    @endif
                </div>

            </div>

            <!-- ── RIGHT: Sidebar ──────────────────────────── -->
            <div class="sidebar-stack">

                <!-- Details card -->
                <div class="card-clean detail-card">
                    <p class="detail-card__header">Event Details</p>

                    <!-- Google Map -->
                    <div id="map"></div>

                    <div class="detail-list">
                        <!-- Date -->
                        <div class="detail-item">
                            <div class="detail-icon">
                                <i class="ri-calendar-event-line"></i>
                            </div>
                            <div class="detail-content">
                                <div class="detail-content__label">Date</div>
                                <div class="detail-content__value" id="event-start-date-tag">
                                    {{ Carbon::parse($event->start_date)->format('F d, Y') }}
                                    &mdash;
                                    {{ Carbon::parse($event->end_date)->format('F d, Y') }}
                                </div>
                            </div>
                        </div>
                        <!-- Time -->
                        <div class="detail-item">
                            <div class="detail-icon">
                                <i class="ri-time-line"></i>
                            </div>
                            <div class="detail-content">
                                <div class="detail-content__label">Time</div>
                                <div class="detail-content__value">
                                    {{ Carbon::parse($event->time)->format('h:i A') }}
                                </div>
                            </div>
                        </div>
                        <!-- Location -->
                        <div class="detail-item">
                            <div class="detail-icon">
                                <i class="ri-map-pin-line"></i>
                            </div>
                            <div class="detail-content">
                                <div class="detail-content__label">Location</div>
                                <div class="detail-content__value" id="event-location-tag">
                                    {{ $event->location }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Fee highlight -->
                    <div class="fee-highlight">
                        <div>
                            <div class="fee-label">Registration Fee</div>
                            <div class="fee-amount" id="event-registrationfee-tag">
                                ₱ {{ number_format($event->reg_fee, 2) }}
                            </div>
                        </div>
                        <div class="fee-icon">
                            <i class="ri-money-dollar-circle-line"></i>
                        </div>
                    </div>

                </div>

                <!-- Register CTA -->
                @if ($event->is_enable_event_registration)
                    <div class="card-clean register-card">
                        <a href="{{ route('events.register.public', ['event' => $event->id]) }}" class="btn-register">
                            <i class="ri-user-add-line" style="font-size:16px;"></i>
                            &nbsp;Register Now
                        </a>
                        <p class="register-note">
                            Review the event details before registering. <br>
                            Spots may be limited.
                        </p>
                    </div>
                @endif

                <!-- Social Media -->
                <div class="card-clean social-card">
                    <p class="section-label">Follow Us</p>
                    <div class="social-links">
                        <a href="" title="Facebook" class="social-btn social-btn--fb">
                            <i class="ri-facebook-fill"></i>
                        </a>
                        <a href="" title="Instagram" class="social-btn social-btn--ig">
                            <i class="ri-instagram-fill"></i>
                        </a>
                        <a href="" title="YouTube" class="social-btn social-btn--yt">
                            <i class="ri-youtube-fill"></i>
                        </a>
                    </div>
                </div>

            </div>
            <!-- end sidebar -->

        </div>
        <!-- end grid -->

    </div>
    <!-- end page -->

    <!-- Hidden inputs -->
    <input type="hidden" name="latitude" id="latitude" value="{{ $event->latitude }}">
    <input type="hidden" name="longitude" id="longitude" value="{{ $event->longitude }}">

    <script async
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDF42hfO7Dj8XFLrJY_SSF1bBM2Dj5XLQQ&libraries=places&callback=initialize">
    </script>
    <script>
        function initialize() {
            const latitude = document.querySelector('#latitude');
            const longitude = document.querySelector('#longitude');
            const mapOptions = {
                center: latitude.value && longitude.value ?
                    new google.maps.LatLng(latitude.value, longitude.value) : new google.maps.LatLng(14.5995124,
                        120.9842195),
                zoom: 14,
                disableDefaultUI: false,
                scrollwheel: true,
                draggable: true,
                styles: [{
                        elementType: 'geometry',
                        stylers: [{
                            color: '#f5f7fb'
                        }]
                    },
                    {
                        elementType: 'labels.text.fill',
                        stylers: [{
                            color: '#516091'
                        }]
                    },
                    {
                        featureType: 'road',
                        elementType: 'geometry',
                        stylers: [{
                            color: '#ffffff'
                        }]
                    },
                    {
                        featureType: 'water',
                        elementType: 'geometry',
                        stylers: [{
                            color: '#c8d8ec'
                        }]
                    },
                    {
                        featureType: 'poi.park',
                        elementType: 'geometry',
                        stylers: [{
                            color: '#d9ead3'
                        }]
                    },
                ]
            };

            const map = new google.maps.Map(document.querySelector('#map'), mapOptions);

            if (latitude.value && longitude.value) {
                new google.maps.Marker({
                    position: new google.maps.LatLng(Number(latitude.value), Number(longitude.value)),
                    map: map,
                    draggable: false,
                    icon: {
                        path: google.maps.SymbolPath.CIRCLE,
                        scale: 8,
                        fillColor: '#3B5EA6',
                        fillOpacity: 1,
                        strokeColor: '#ffffff',
                        strokeWeight: 2,
                    }
                });
            }
        }
    </script>
@endsection
