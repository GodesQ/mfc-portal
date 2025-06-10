@extends('layouts.master-without-nav')

@section('title')
    Payment Failed
@endsection

@section('body')

    <body class="auth-body-bg">
    @endsection

    @section('content')
        <!-- auth-page wrapper -->
        <div class="auth-page-wrapper py-5 d-flex justify-content-center align-items-center min-vh-100">

            <!-- auth-page content -->
            <div class="auth-page-content overflow-hidden p-0">
                <div class="container-fluid">
                    <div class="row justify-content-center">
                        <div class="col-xl-4 text-center">
                            <div class=" position-relative mb-5">
                                <img src="https://i.pinimg.com/736x/fd/69/de/fd69de8b4320fab07298c882f18e6df5.jpg"
                                    alt="Internal Server Error" class="img-fluid error-500-img"
                                    style="width: 250px; height: 250px; object-fit: cover; border-radius: 50%;" />
                            </div>
                            <div>
                                <h4>Payment Failed!</h4>
                                <p class="text-muted w-75 mx-auto">We encountered an issue while processing your payment.
                                    Our servers reported an internal error, and we're working to resolve it.</p>

                                <div class="mt-4">
                                    <p>What you can do:</p>
                                    <ul class="list-unstyled text-muted mb-4">
                                        <li>• Try the payment again in a few minutes</li>
                                        <li>• Check your payment method details</li>
                                        <li>• Contact support if the problem persists</li>
                                    </ul>
                                </div>

                                <a href="index" class="btn btn-success"><i class="mdi mdi-home me-1"></i>Back to home</a>
                                <a href="contact-us" class="btn btn-info ms-2"><i class="mdi mdi-email me-1"></i>Contact
                                    Support</a>
                            </div>
                        </div><!-- end col-->
                    </div>
                    <!-- end row -->
                </div>
                <!-- end container -->
            </div>
            <!-- end auth-page content -->
        </div>
        <!-- end auth-page-wrapper -->
    @endsection
