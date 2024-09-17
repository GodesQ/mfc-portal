@extends('layouts.master-without-nav')
@section('title')
    @lang('translation.signup')
@endsection
@section('content')
    <div class="auth-page-wrapper pt-5">
        <!-- auth page bg -->
        <div class="auth-one-bg-position auth-one-bg" id="auth-particles">
            <div class="bg-overlay"></div>

            <div class="shape">
                <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink"
                    viewBox="0 0 1440 120">
                    <path d="M 0,36 C 144,53.6 432,123.2 720,124 C 1008,124.8 1296,56.8 1440,40L1440 140L0 140z"></path>
                </svg>
            </div>
        </div>

        <!-- auth page content -->
        <div class="auth-page-content">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center mt-sm-5 mb-4 text-white-50">
                            <div>
                                <a href="index" class="d-inline-block auth-logo">
                                    <img src="{{ URL::asset('build/images/logo-wide-white.png') }}" alt=""
                                        height="150">
                                </a>
                            </div>
                            <p class="mt-3 fs-15 fw-medium">Let's bring the evangelization online.</p>
                        </div>
                    </div>
                </div>
                <!-- end row -->

                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-5">
                        <div class="card mt-4">

                            <div class="card-body p-4">
                                <div class="text-center mt-2">
                                    <h5 class="text-primary">Create New Account</h5>
                                    <p class="text-muted">Get your free velzon account now</p>
                                </div>
                                <div class="p-2 mt-4">
                                    <form class="needs-validation" novalidate method="POST" id="registerForm"
                                        action="{{ route('register') }}" enctype="multipart/form-data">
                                        @csrf
                                        <x-input_fields.name id="userFirstName" label="First Name" name="firstname"
                                            value="{{ old('firstname') }}"
                                            feedback="Please enter your first name"></x-input_fields.name>
                                        <x-input_fields.name id="userLastName" label="Last Name" name="lastname"
                                            value="{{ old('lastname') }}"
                                            feedback="Please enter your last name"></x-input_fields.name>
                                        <x-input_fields.email id="useremail" name="email"
                                            value="{{ old('email') }}"></x-input_fields.email>
                                        <div class="mb-3">
                                            <label for="username-field" class="form-label">Username</label>
                                            <input type="text" class="form-control" name="username" id="username-field" placeholder="Enter Your username">
                                        </div>
                                        <div class="mb-3">
                                            <label for="password-field" class="form-label">Password</label>
                                            <input type="password" class="form-control" name="password" id="password-field" placeholder="Enter Your password">
                                        </div>
                                        <x-input_fields.contact-number id="usercontact" name="contact_number"
                                            formId="registerForm"></x-input_fields.contact-number>
                                        <x-input_fields.choices label="MFC Section" id="mfc_section" formId="registerForm" name="section">
                                            <option value="kids" class="text-capitalize">kids</option>
                                            <option value="youth" class="text-capitalize">youth</option>
                                            <option value="singles" class="text-capitalize">singles</option>
                                            <option value="handmaids" class="text-capitalize">handmaids</option>
                                            <option value="servants" class="text-capitalize">servants</option>
                                            <option value="couples" class="text-capitalize">couples</option>
                                        </x-input_fields.choices>

                                        <div class="mb-3">
                                            <p class="mb-0 fs-12 text-muted fst-italic d-flex gap-1">By registering you
                                                agree to the
                                                Velzon <a href="#"
                                                    class="text-primary text-decoration-underline fst-normal fw-medium">Terms
                                                    of Use</a></p>
                                        </div>

                                        <div class="mt-3">
                                            <button class="btn btn-success w-100" type="submit">Sign Up</button>
                                        </div>
                                    </form>

                                </div>
                            </div>
                            <!-- end card body -->
                        </div>
                        <!-- end card -->

                        <div class="mt-4 text-center">
                            <p class="mb-0">Already have an account? <a href="{{ route('login') }}"
                                    class="fw-semibold text-primary text-decoration-underline"> Sign In </a> </p>
                        </div>

                    </div>
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </div>
        <!-- end auth page content -->

        <!-- footer -->
        <footer class="footer">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center">
                            <p class="mb-0 text-muted">&copy; <script>document.write(new Date().getFullYear())</script> MFC Portal. Crafted with <i class="mdi mdi-heart text-danger"></i> by <a href="https://godesq.com/" target="_blank">GodesQ Digital Marketing Services</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- end Footer -->
    </div>
    <!-- end auth-page-wrapper -->
@endsection
@section('script')
    <script src="{{ URL::asset('build/libs/particles.js/particles.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/particles.app.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/password-addon.init.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/form-validation.init.js') }}"></script>
    <script src="{{ URL::asset('build/libs/prismjs/prism.js') }}"></script>
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection
