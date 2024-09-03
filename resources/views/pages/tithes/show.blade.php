@extends('layouts.master')
@section('title')
    @lang('translation.tithes')
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Tithes
        @endslot
        @slot('title')
            {{ $endPoint }}
        @endslot
    @endcomponent

    <div class="container">
        <div class="row mt-3">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <div class="container-fluid my-3">
                            <div class="mb-3 border-bottom">
                                <h3>User Details</h3>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 my-2">
                                    <label class="text-primary" for="firstname">First Name</label>
                                    <h5 class="font-bold fs-13 text-black">{{ $tithe->user->first_name }}</h5>
                                </div>
                                <div class="col-lg-4 my-2">
                                    <label class="text-primary" for="lastname">Last Name</label>
                                    <h5 class="font-bold fs-13 text-black">{{ $tithe->user->last_name  }}</h5>
                                </div>
                                <div class="col-lg-4 my-2">
                                    <label class="text-primary" for="middlename">Middle Name</label>
                                    <h5 class="font-bold fs-13 text-black">{{ $tithe->user->middle_name ?? 'N/A' }}</h5>
                                </div>
                                <div class="col-lg-4 my-2">
                                    <label class="text-primary" for="username">Username</label>
                                    <h5 class="font-bold fs-13 text-black">{{ $tithe->user->username ?? 'N/A' }}</h5>
                                </div>
                                <div class="col-lg-4 my-2">
                                    <label class="text-primary" for="email">Email</label>
                                    <h5 class="font-bold fs-13 text-black">{{ $tithe->user->email  }}</h5>
                                </div>
                                <div class="col-lg-4 my-2">
                                    <label class="text-primary" for="contact_number">Contact Number</label>
                                    <h5 class="font-bold fs-13 text-black">{{ $tithe->user->contact_number ?? 'N/A' }}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="container-fluid my-3">
                            <div class="mb-3 border-bottom">
                                <h3>Transaction Details</h3>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 my-2">
                                    <label class="text-primary" for="firstname">Transaction Code</label>
                                    <h5 class="font-bold fs-13 text-black">{{ $tithe->transaction->transaction_code }}</h5>
                                </div>
                                <div class="col-lg-4 my-2">
                                    <label class="text-primary" for="lastname">Reference Code</label>
                                    <h5 class="font-bold fs-13 text-black">{{ $tithe->transaction->reference_code  }}</h5>
                                </div>
                                <div class="col-lg-4 my-2">
                                    <label class="text-primary" for="middlename">Checkout ID</label>
                                    <h5 class="font-bold fs-13 text-black">{{ $tithe->transaction->checkout_id }}</h5>
                                </div>
                                <div class="col-lg-12 my-2">
                                    <label class="text-primary" for="middlename">Payment Link</label> <br>
                                    <a href="{{ $tithe->transaction->payment_link }}" class="text-black">{{ $tithe->transaction->payment_link }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3 border-bottom">
                            <h3>Tithe Summary</h3>
                        </div>
                        <div class="row my-2">
                            <div class="col-xl-6">
                                <h6 class="text-primary">Sub Amount</h6>
                            </div>
                            <div class="col-xl-6">
                                <h6 id="sub_amount_text">₱ {{ number_format($tithe->transaction->sub_amount, 2) }}</h6>
                            </div>
                        </div>
                        <div class="row my-2">
                            <div class="col-xl-6">
                                <h6 class="text-primary">Total Amount</h6>
                            </div>
                            <div class="col-xl-6">
                                <h6 id="total_amount_text">₱ {{ number_format($tithe->transaction->total_amount, 2) }}</h6>
                            </div>
                        </div>
                        <div class="row my-2">
                            <div class="col-xl-6">
                                <h6 class="text-primary">Status</h6>
                            </div>
                            <div class="col-xl-6">
                                @if($tithe->status == 'paid')
                                    <div class="badge bg-success">Paid</div>
                                @elseif ($tithe->status == 'unpaid')
                                    <div class="badge bg-warning">Unpaid</div>
                                @else
                                    <div class="badge bg-info">{{ $tithe->status }}</div>
                                @endif
                            </div>
                        </div>
                        <hr>
                        @if($tithe->status == 'unpaid')
                            <a href="{{ $tithe->transaction->payment_link }}" class="btn btn-primary" style="width: 100%;">Pay</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
