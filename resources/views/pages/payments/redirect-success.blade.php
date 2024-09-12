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
    <link rel="stylesheet" href="{{ URL::asset('build/css/custom.min.css') }}">
</head>

<body>
    <div class="my-4">
        <div class="row justify-content-center">
            <div class="col-xxl-7">
                <div class="card" id="demo">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card-header border-bottom-dashed border-2 pt-4 pb-2 px-4">
                                <div class="d-block text-center mb-3" id="ar-header-box">
                                    <div class="header-logo d-flex justify-content-center">
                                        <img src="{{ URL::asset('build/images/mfc-logo-retina.png') }}"
                                            class="card-logo card-logo-dark" alt="logo dark" width="360">
                                        <img src="{{ URL::asset('build/images/mfc-logo-retina.png') }}"
                                            class="card-logo card-logo-light" alt="logo light" width="360">
                                    </div>
                                    <div class="mt-sm-0 mt-3" id="header-details">
                                        <h6>
                                            <span class=" fw-normal text-wrap" style="line-height: 20px !important;">
                                            12 Starmall Complex, Shaw Blvd., Wack-Wack Greenhills, 1555 City of Mandaluyong NCR, Second District, Philippines
                                        </h6>
                                        <h6>
                                            <span class="fw-normal">Email:</span>
                                            <span id="email" class="link-primary">Email@missionaryfamiliesofChrist.org</span>
                                        </h6>
                                        <h6 >
                                            <span class="fw-normal">Tel/Fax No: </span>
                                            <span id="contact-no">63(2) 77182213</span>
                                        </h6>
                                        <h6>
                                            <span class="fw-normal">Non-VAT Reg. TIN:</span>
                                            <span id="vat-no">010-349-685-00000</span>
                                        </h6>
                                    </div>
                                </div>
                                {{-- <h3 class="text-center mt-4">ACKNOWLEDGEMENT RECEIPT</h3> --}}
                            </div>
                            <!--end card-header-->
                        </div>
                        <!--end col-->
                        <div class="col-lg-12">
                            <div class="row py-4 w-full">
                                <div class="col-lg-11 col-sm-8 text-center">
                                    <h3 style="font-size: 25px; font-weight: 500;">Acknowledgement Receipt</h3>
                                </div>
                                <div class="col-lg-1 col-sm-4 text-sm-center">
                                    <h4 style="font-size: 16px;"><span class="fw-bold">No.</span> <span>{{ sprintf("%02d", $transaction->id) }}</span></h4>
                                </div>
                            </div>
                            <div class="card-body p-4">
                                <div class="row g-3">
                                    <div class="col-lg-3 col-6">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">Reference Code</p>
                                        <h5 class="fs-14 mb-0"><span id="invoice-no">{{ $transaction->reference_code }}</span></h5>
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
                                    <div class="col-lg-3 col-6">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">Received From</p>
                                        <h5 class="fs-14 mb-0">
                                            <span id="received-from"> {{ ($transaction->received_from_user->first_name ?? "No First Name Found") . ' ' . ($transaction->received_from_user->last_name ?? "No Last Name Found") }}</span>
                                            <br>
                                            <span class="text-muted fs-12">#{{ ($transaction->received_from_user->mfc_id_number ?? "No MFC ID Number Found") }}</span>
                                        </h5>
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
                                                <th scope="col" style="text-align: left;">Details</th>
                                                <th scope="col" style="text-align: left;">As Payment For</th>
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
                                                        <p class="text-muted mb-0">#{{ $item['mfc_id_number'] }}
                                                        </p>
                                                    </td>
                                                    <td class="text-start">{{ $item['payment_type'] }}</td>
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
