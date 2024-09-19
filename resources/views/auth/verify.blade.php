@extends('layouts.master-without-nav')
@section('title')
    @lang('translation.two-step-verification')
@endsection
@section('content')
    <!-- auth-page wrapper -->
    <div class="auth-page-wrapper auth-bg-cover py-5 d-flex justify-content-center align-items-center min-vh-100">
        <div class="bg-overlay"></div>
        <!-- auth-page content -->
        <div class="auth-page-content overflow-hidden pt-lg-5">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card overflow-hidden">
                            <div class="row justify-content-center g-0">
                                <div class="col-lg-6">
                                    <div class="p-lg-5 p-4 auth-one-bg h-100">
                                        <div class="bg-overlay"></div>
                                        <div class="position-relative h-100 d-flex flex-column">
                                            <div class="h-100 d-flex justify-content-center align-items-center">
                                                <a href="index" class="d-block">
                                                    <img src="{{ URL::asset('build/images/mfc-logo-retina-white.png') }}"
                                                        alt="" height="40" >
                                                </a>
                                            </div>
                                            <div class="mt-auto">
                                                {{-- <div class="mb-3">
                                                    <i class="ri-double-quotes-l display-4 text-success"></i>
                                                </div> --}}

                                                {{-- <div id="qoutescarouselIndicators" class="carousel slide"
                                                    data-bs-ride="carousel">
                                                    <div class="carousel-indicators">
                                                        <button type="button" data-bs-target="#qoutescarouselIndicators"
                                                            data-bs-slide-to="0" class="active" aria-current="true"
                                                            aria-label="Slide 1"></button>
                                                        <button type="button" data-bs-target="#qoutescarouselIndicators"
                                                            data-bs-slide-to="1" aria-label="Slide 2"></button>
                                                        <button type="button" data-bs-target="#qoutescarouselIndicators"
                                                            data-bs-slide-to="2" aria-label="Slide 3"></button>
                                                    </div>
                                                    <div class="carousel-inner text-center text-white-50 pb-5">
                                                        <div class="carousel-item active">
                                                            <p class="fs-15">" Great! Clean code, clean design, easy for
                                                                customization. Thanks very much! "</p>
                                                        </div>
                                                        <div class="carousel-item">
                                                            <p class="fs-15">" The theme is really great with an amazing
                                                                customer support."</p>
                                                        </div>
                                                        <div class="carousel-item">
                                                            <p class="fs-15">" Great! Clean code, clean design, easy for
                                                                customization. Thanks very much! "</p>
                                                        </div>
                                                    </div>
                                                </div> --}}
                                                <!-- end carousel -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- end col -->

                                <div class="col-lg-6">
                                    <div class="p-lg-5 p-4">
                                        <div class="mb-4">
                                            <div class="avatar-lg mx-auto">
                                                <div class="avatar-title bg-light text-primary display-5 rounded-circle">
                                                    <i class="ri-mail-line"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-muted text-center mx-lg-3">
                                            <h4 class="">Verify Your Email</h4>
                                            <p>Please enter the 4 digit code sent to <span
                                                    class="fw-semibold">{{ Auth::user()->email }}</span></p>
                                        </div>

                                        <div class="mt-4">
                                            <form autocomplete="off" action="{{ route('verification.verify') }}"
                                                method="POST">
                                                @csrf
                                                <div class="row">
                                                    <div class="col-3">
                                                        <div class="mb-3">
                                                            <label for="digit1-input" class="visually-hidden">Digit
                                                                1</label>
                                                            <input type="text"
                                                                class="form-control form-control-lg bg-light border-light text-center"
                                                                onkeyup="moveToNext(1,event)" maxLength="1"
                                                                id="digit1-input">
                                                        </div>
                                                    </div><!-- end col -->

                                                    <div class="col-3">
                                                        <div class="mb-3">
                                                            <label for="digit2-input" class="visually-hidden">Digit
                                                                2</label>
                                                            <input type="text"
                                                                class="form-control form-control-lg bg-light border-light text-center"
                                                                onkeyup="moveToNext(2,event)" maxLength="1"
                                                                id="digit2-input">
                                                        </div>
                                                    </div><!-- end col -->

                                                    <div class="col-3">
                                                        <div class="mb-3">
                                                            <label for="digit3-input" class="visually-hidden">Digit
                                                                3</label>
                                                            <input type="text"
                                                                class="form-control form-control-lg bg-light border-light text-center"
                                                                onkeyup="moveToNext(3,event)" maxLength="1"
                                                                id="digit3-input">
                                                        </div>
                                                    </div><!-- end col -->

                                                    <div class="col-3">
                                                        <div class="mb-3">
                                                            <label for="digit4-input" class="visually-hidden">Digit
                                                                4</label>
                                                            <input type="text"
                                                                class="form-control form-control-lg bg-light border-light text-center"
                                                                onkeyup="moveToNext(4,event)" maxLength="1"
                                                                id="digit4-input">
                                                        </div>
                                                    </div><!-- end col -->
                                                </div>

                                                <input type="text" name="otp" id="otp_input" hidden>

                                                <script>
                                                    document.addEventListener('DOMContentLoaded', function() {
                                                        var digitInputs = document.querySelectorAll('.form-control');
                                                        var otpInput = document.getElementById('otp_input');
                                                        var value = "";

                                                        digitInputs.forEach(input => {
                                                            input.addEventListener('input', function() {
                                                                value += input.value;
                                                                otpInput.value = value;

                                                                console.log(otpInput.value);

                                                            });
                                                        });

                                                    });
                                                </script>

                                                <div class="mt-3">
                                                    <button type="submit" class="btn btn-success w-100">Confirm</button>
                                                </div>

                                            </form>

                                        </div>

                                        <div class="mt-3 text-center">
                                            <div class="alert alert-success alert-dismissible fade show material-shadow text-center mb-3 d-none"
                                                id="alert_success" role="alert">
                                                <strong>Success!</strong> Verification code has been sent to
                                                <span class="fw-semibold">{{ Auth::user()->email }}</span>.
                                                <button type="button" class="btn-close"
                                                    data-bs-dismiss="alert"></button>
                                            </div>
                                            <p class="mb-0 d-flex justify-content-center align-items-center gap-1">Didn't
                                                receive a code ? <a href="javascript:void(0);" id="resend"
                                                    class="fw-semibold text-primary text-decoration-underline"><span
                                                        class="" id="resend-text">Resend</span></a>
                                                <span class="d-none" id="spinner">
                                                    <script src="https://cdn.lordicon.com/lordicon.js"></script>
                                                    <lord-icon src="https://cdn.lordicon.com/gkryirhd.json" trigger="loop"
                                                        state="loop-snake" style="width:25px;height:25px">
                                                    </lord-icon>
                                                </span>

                                                <script>
                                                    $(document).ready(function() {
                                                        $('#resend').on('click', function() {
                                                            $('#resend-text').addClass('d-none');
                                                            $('#spinner').removeClass('d-none');

                                                            $.ajax({
                                                                headers: {
                                                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                                                },
                                                                url: "{{ route('verification.send') }}",
                                                                type: 'POST',
                                                                success: function(response) {
                                                                    console.log('success');

                                                                    $('#resend-text').removeClass('d-none');
                                                                    $('#spinner').addClass('d-none');

                                                                    $('#alert_success').removeClass('d-none');
                                                                },
                                                                error: function(error) {
                                                                    console.log(error.responseJSON.message);
                                                                    $('#resend-text').removeClass('d-none');
                                                                    $('#spinner').addClass('d-none');
                                                                }
                                                            })
                                                        })
                                                    })
                                                </script>
                                            </p>
                                        </div>

                                        <div class="mt-3 text-center">
                                            <a class="fw-semibold text-danger text-decoration-underline"
                                                href="javascript:void();"
                                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><span
                                                    key="t-logout">@lang('translation.logout')</span></a>
                                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                                style="display: none;">
                                                @csrf
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!-- end col -->
                            </div>
                            <!-- end row -->
                        </div>
                        <!-- end card -->
                    </div>
                    <!-- end col -->

                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </div>
        <!-- end auth page content -->

        <!-- footer -->
        @include('layouts.footer')
        <!-- end Footer -->
    </div>
@endsection
@section('script')
    <script src="{{ URL::asset('build/js/pages/two-step-verification.init.js') }}"></script>
@endsection
