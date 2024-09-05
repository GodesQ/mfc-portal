<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice</title>
    <script src="{{ URL::asset('build/js/layout.js') }}"></script>
    <link rel="stylesheet" href="{{ URL::asset('build/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('build/css/icons.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('build/css/app.min.css') }}">
</head>

<body>
    <div class="my-4">
        <div class="row justify-content-center">
            <div class="col-xxl-6">
                <div class="card" id="demo">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card-header border-bottom-dashed p-4">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <img src="{{ URL::asset('build/images/MFC-Logo.jpg') }}"
                                            class="card-logo card-logo-dark" alt="logo dark" height="80">
                                        <img src="{{ URL::asset('build/images/MFC-Logo.jpg') }}"
                                            class="card-logo card-logo-light" alt="logo light" height="80">
                                    </div>
                                    {{-- <div class="flex-shrink-0 mt-sm-0 mt-3">
                                        <h6><span class="text-muted fw-normal">Legal
                                                Registration No:</span>
                                            <span id="legal-register-no">987654</span>
                                        </h6>
                                        <h6><span class="text-muted fw-normal">Email:</span>
                                            <span id="email">velzon@themesbrand.com</span>
                                        </h6>
                                        <h6><span class="text-muted fw-normal">Website:</span> <a
                                                href="https://themesbrand.com/" class="link-primary" target="_blank"
                                                id="website">www.themesbrand.com</a></h6>
                                        <h6 class="mb-0"><span class="text-muted fw-normal">Contact No: </span><span
                                                id="contact-no"> +(01) 234 6789</span></h6>
                                    </div> --}}
                                </div>
                            </div>
                            <!--end card-header-->
                        </div>
                        <!--end col-->
                        <div class="col-lg-12">
                            <div class="card-body p-4">
                                <div class="row g-3">
                                    <div class="col-lg-3 col-6">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">Transaction Code</p>
                                        <h5 class="fs-14 mb-0"><span id="invoice-no">{{ $transaction->transaction_code }}</span></h5>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-3 col-6">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">Date</p>
                                        <h5 class="fs-14 mb-0">
                                            <span id="invoice-date">{{ Carbon::parse($transaction->created_at)->format('M d, Y') }}</span> 
                                            <small class="text-muted" id="invoice-time">{{ Carbon::parse($transaction->created_at)->format("H:i A") }}</small>
                                        </h5>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-3 col-6">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">Payment Status</p>
                                        <span class="badge bg-success-subtle text-success fs-11"
                                            id="payment-status">Paid</span>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-3 col-6">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">Total Amount</p>
                                        <h5 class="fs-14 mb-0">₱<span id="total-amount"> {{ number_format($transaction->total_amount, 2) }}</span></h5>
                                    </div>
                                    <!--end col-->
                                </div>
                                <!--end row-->
                            </div>
                            <!--end card-body-->
                        </div>
                        <!--end col-->
                        
                        <div class="col-lg-12">
                            <div class="card-body p-4">
                                <div class="table-responsive">
                                    <table class="table table-borderless text-center table-nowrap align-middle mb-0">
                                        <thead>
                                            <tr class="table-active">
                                                <th scope="col" style="width: 50px;">#</th>
                                                <th scope="col">Details</th>
                                                <th scope="col">Payment Type</th>
                                                <th scope="col">Date</th>
                                                <th scope="col" class="text-end">Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody id="products-list">
                                            @forelse ($items as $item)
                                                <tr>
                                                    <th scope="row">{{ $item['id'] }}</th>
                                                    <td class="text-start">
                                                        <span class="fw-medium">{{ $item['name'] }}</span>
                                                        <p class="text-muted mb-0">{{ $item['mfc_id_number'] }}
                                                        </p>
                                                    </td>
                                                    <td>{{ $item['payment_type'] }}</td>
                                                    <td>{{ $item['date'] }}</td>
                                                    <td class="text-end">₱ {{ number_format($item['amount'], 2) }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6">No Items Found</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                    <!--end table-->
                                </div>
                                <div class="border-top border-top-dashed mt-2">
                                    <table class="table table-borderless table-nowrap align-middle mb-0 ms-auto"
                                        style="width:250px">
                                        <tbody>
                                            <tr>
                                                <td>Sub Total</td>
                                                <td class="text-end">₱ {{ number_format($transaction->sub_amount, 2) }}</td>
                                            </tr>
                                            <tr>
                                                <td>Donation</td>
                                                <td class="text-end">₱ {{ number_format($transaction->donation, 2) }}</td>
                                            </tr>
                                            <tr>
                                                <td>Convenience Fee</td>
                                                <td class="text-end">₱ {{ number_format($transaction->convenience_fee, 2) }}</td>
                                            </tr>
                                            <tr class="border-top border-top-dashed fs-15">
                                                <th scope="row">Total Amount</th>
                                                <th class="text-end">₱ {{ number_format($transaction->total_amount, 2) }}</th>
                                            </tr>
                                        </tbody>
                                    </table>
                                    </table>
                                    <!--end table-->
                                </div>
                                <div class="hstack gap-2 justify-content-end d-print-none mt-4">
                                    <a href="/" class="btn btn-dark"><i class="ri-arrow-left-line"></i> Back to Dashboard</a>
                                    <a href="javascript:window.print()" class="btn btn-success"><i
                                            class="ri-printer-line align-bottom me-1"></i> Print</a>
                                    <a href="javascript:void(0);" class="btn btn-primary"><i
                                            class="ri-download-2-line align-bottom me-1"></i> Download</a>
                                </div>
                            </div>
                            <!--end card-body-->
                        </div>
                        <!--end col-->
                    </div>
                    <!--end row-->
                </div>
                <!--end card-->
            </div>
            <!--end col-->
        </div>
    </div>
    <!--end row-->
</body>

</html>
