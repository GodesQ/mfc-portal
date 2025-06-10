@extends('layouts.master-without-nav')

@section('title')
    Payment Canceled
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
                                <img src="https://static.vecteezy.com/system/resources/previews/043/184/474/non_2x/no-payment-method-selected-yet-add-it-now-concept-illustration-flat-design-simple-modern-graphic-element-for-empty-state-ui-infographic-icon-vector.jpg"
                                    alt="payment canceled" class="img-fluid error-500-img"
                                    style="width: 250px; height: 250px; object-fit: cover; border-radius: 50%;" />
                            </div>
                            <div>
                                <h4 class="text-xl">Payment Canceled!</h4>
                                <p class="text-muted w-75 mx-auto">Your payment was not completed. This could be because
                                    you chose to cancel or the session expired.</p>

                                <div class="mt-4">
                                    <p>What Happened?</p>
                                    <ul class="list-unstyled text-muted mb-4">
                                        <li>• You or your bank stopped the payment.</li>
                                        <li>• The transaction timed out (took too long).</li>
                                        <li>• There was a temporary issue with the payment method.</li>
                                    </ul>
                                </div>

                                <a href="/" class="btn btn-success"><i class="mdi mdi-home me-1"></i>Back to home</a>
                                <a href="/contact-us" class="btn btn-info ms-2"><i class="mdi mdi-email me-1"></i>Contact
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
