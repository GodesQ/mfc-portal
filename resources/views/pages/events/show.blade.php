<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $event->title }} | MFC Event</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ URL::asset('build/images/favicon.ico') }}">
    @include('layouts.head-css')
    <style>
        #map {
            width: 100%;
            height: 300px;
            border-radius: 5px;
        }
    </style>
</head>

<body>

    <nav class="main-nav">
        <div class="d-flex justify-content-between align-items-center">
            <img class="rounded-circle" style="width: 60px;" src="{{ URL::asset('build/images/MFC-Logo.jpg') }}" alt="">
            @if(auth()->user())
                <div>
                    <a href="{{ route('dashboards.index') }}" class="btn btn-primary">Dashboard</a>
                </div>
            @else
                <ul class="mb-[0px] d-flex gap-3">
                    <li><a href="{{ route('login') }}" class="btn btn-primary-50">Login</a></li>
                    <li><a href="{{ route('register') }}" class="btn btn-primary">Sign Up</a></li>
                </ul>
            @endif
        </div>
    </nav>

    <div class="container my-4">
        <div class="row">
            <div class="col-lg-7">
                <div class="card">
                    <div class="card-body">
                        <h3>{{ $event->title }}</h3>
                        <div class="flex gap-2">
                            <span class="bg-primary badge text-uppercase">Worldwide</span>
                            <span class="badge bg-primary text-uppercase">{{ $event->section->name }}</span>
                        </div>
                        <div class="my-2">
                            {!! $event->description !!}
                        </div>
                        <div class="my-1">
                            <img src="{{ URL::asset('uploads/' . $event->poster) }}" alt="" class="rounded"
                                style="width: 100%;">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="card">
                    <div class="card-body">
                        <div id="map"></div>
                        <div class="event-details my-3">
                            <div class="d-flex mb-2">
                                <div class="flex-grow-1 d-flex align-items-center">
                                    <div class="flex-shrink-0 me-3">
                                        <i class="ri-calendar-event-line text-muted fs-16"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="d-block fw-semibold mb-0" id="event-start-date-tag">
                                            {{ Carbon::parse($event->start_date)->format('F d, Y') }}
                                            -
                                            {{ Carbon::parse($event->end_date)->format('F d, Y') }}
                                        </h6>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-shrink-0 me-3">
                                    <i class="ri-time-line text-muted fs-16"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="d-block fw-semibold mb-0">
                                        {{ Carbon::parse($event->time)->format('H:i A') }}
                                    </h6>
                                </div>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-shrink-0 me-3">
                                    <i class="ri-map-pin-line text-muted fs-16"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="d-block fw-semibold mb-0">
                                        <span id="event-location-tag">
                                            {{ $event->location }}
                                        </span>
                                    </h6>
                                </div>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-shrink-0 me-3">
                                    <i class="ri-money-dollar-box-line text-muted fs-16"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="d-block fw-semibold mb-0" id="event-registrationfee-tag">
                                        ₱ {{ number_format($event->reg_fee, 2) }}
                                    </p>
                                </div>
                            </div>
                            @if ($event->is_enable_event_registration)
                                <button type="button" class="btn btn-primary" style="width: 100%;"
                                    id="register-event-btn">Register Now
                                </button>
                            @endif
                        </div>
                        @csrf
                        <input type="hidden" id="event_id" value="{{ $event->id }}">
                        <input type="hidden" name="latitude" id="latitude" value="{{ $event->latitude }}">
                        <input type="hidden" name="longitude" id="longitude" value="{{ $event->longitude }}">
                    </div>
                </div>
                <div class="social-media-icons">
                    <h3 class="mb-1" style="font-size: 20px;">Our Social Media</h3>
                    <div class="m-[0px] d-flex gap-2" style="list-style: none;">
                        <div >
                            <a href="" title="Facebook">
                                <i class="ri-facebook-fill fs-20"></i>
                            </a>
                        </div>
                        <div>
                            <a href="" title="Instagram">
                                <i class="ri-instagram-fill fs-20" style="color: #f7772f;"></i>
                            </a>
                        </div>
                        <div>
                            <a href="" title="Youtube">
                                <i class="ri-youtube-fill fs-20" style="color: red;"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ URL::asset('build/libs/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <script async
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDEmTK1XpJ2VJuylKczq2-49A6_WuUlfe4&libraries=places&callback=initialize">
    </script>
    <script>
        function initialize() {
            let latitude = document.querySelector('#latitude');
            let longitude = document.querySelector('#longitude');
            var mapOptions = {
                center: latitude.value && longitude.value ? new google.maps.LatLng(latitude.value, longitude.value) :
                    new google.maps.LatLng(14.5995124, 120.9842195),
                zoom: 14,
                disableDefaultUI: false, // Disables the controls like zoom control on the map if set to true
                scrollWheel: true, // If set to false disables the scrolling on the map.
                draggable: true, // If set to false , you cannot move the map around.
            };

            map = new google.maps.Map(document.querySelector("#map"), mapOptions);

            if (latitude.value && longitude.value) {
                let my_marker = new google.maps.Marker({
                    position: new google.maps.LatLng(new google.maps.LatLng(Number(latitude.value), Number(longitude
                        .value))),
                    map: map,
                    draggable: false,
                })
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Register in the event
            document.getElementById("register-event-btn").addEventListener("click", function(e) {
                console.log(e);
                let event_id = $('#event_id').val();
                let token = document.querySelector('input[name="_token"]').value;
                Swal.fire({
                    title: "Confirm Registration",
                    text: "Are you sure you want to proceed with your registration? Please review the event details before submitting.",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonText: "Yes, register now!",
                    showCloseButton: true,
                    confirmButtonColor: '#405189',
                }).then(function(result) {
                    if (result.value) {
                        $.ajax({
                            method: "POST",
                            url: "/dashboard/events/register",
                            headers: {
                                'X-CSRF-TOKEN': token,
                                'accept': "application/json",
                            },
                            data: {
                                event_id: event_id
                            },
                            success: function(response) {
                                window.location.href = response.redirect_url;
                            }
                        })
                    }
                });
            })
        })
    </script>
</body>

</html>
