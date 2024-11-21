@extends('layouts.master')

@section('title')
    @lang('translation.event_registrations')
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Event Registrations
        @endslot
        @slot('title')
            Details
        @endslot
    @endcomponent

    <div class="container-fluid">
        <div class="d-flex justify-content-end align-items-center">
            <a href="#" onclick="window.history.back()" class="btn btn-dark">
                <i class="ri-arrow-left-line"></i>
                Back
            </a>
        </div>
        <div class="row my-3">
            <div class="col-xl-5">
                <div class="card">
                    <div class="card-body">
                        <div class="event-details-container">
                            <div class="border-bottom">
                                <h4>Event Details</h4>
                            </div>
                            <div class="my-3">
                                <div class="fw-bold mb-1">Name</div>
                                <div class="fw-normal">{{ $event_registration->event->title }}</div>
                            </div>
                            <div class="my-3">
                                <div class="fw-bold mb-1">Date</div>
                                <div class="fw-normal">
                                    {{ Carbon::parse($event_registration->event->start_date)->format('M d, Y') }}
                                    -
                                    {{ Carbon::parse($event_registration->event->end_date)->format('M d, Y') }}
                                </div>
                            </div>
                            <div class="my-3">
                                <div class="fw-bold mb-1">Location</div>
                                <div class="fw-normal">
                                    {{ $event_registration->event->location }} <br>
                                </div>
                            </div>
                            <div class="my-3">
                                <div class="fw-bold mb-1">Registration Fee</div>
                                <div class="fw-normal">₱ {{ number_format($event_registration->event->reg_fee, 2) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-7">
                <div class="card">
                    <div class="card-body">
                        <div class="my-2 border px-3 py-2 rounded">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center gap-3">
                                    <img src="{{ URL::asset('uploads/avatars/' . $event_registration->user->avatar) }}"
                                        class="avatar avatar-md" style="border-radius: 50%;" alt="">
                                    <div>
                                        <h3 class="fw-bold">
                                            {{ $event_registration->user->first_name . ' ' . $event_registration->user->last_name }}
                                        </h3>
                                        <h6 class="text-muted">#{{ $event_registration->user->mfc_id_number }}</h6>
                                    </div>
                                </div>
                                <div class="badge bg-primary py-2 px-3">Registered</div>
                            </div>
                        </div>
                        <div class="my-3 px-3">
                            <div class="row gap-3">
                                <div class="col-xl-5 col-5">
                                    <p class="text-muted mb-2 fw-medium">Transaction Code</p>
                                    <h5 class="fs-14 mb-0"><span
                                            id="invoice-no">{{ $event_registration->transaction->transaction_code }}</span>
                                    </h5>
                                </div>
                                <!--end col-->
                                <div class="col-xl-5 col-5">
                                    <p class="text-muted mb-2 fw-medium">Date</p>
                                    <h5 class="fs-14 mb-0">
                                        <span
                                            id="invoice-date">{{ Carbon::parse($event_registration->transaction->created_at)->format('M d, Y') }}</span>
                                        <small class="text-muted"
                                            id="invoice-time">{{ Carbon::parse($event_registration->transaction->created_at)->format('H:i A') }}</small>
                                    </h5>
                                </div>
                                <!--end col-->
                                <div class="col-xl-5 col-5">
                                    <p class="text-muted mb-2 fw-medium">Payment Status</p>
                                    @if ($event_registration->transaction->status == 'paid')
                                        <span class="badge bg-success-subtle text-success fs-11"
                                            id="payment-status">Paid</span>
                                    @else
                                        <span class="badge bg-warning-subtle text-warning fs-11"
                                            id="payment-status">Unpaid</span>
                                    @endif
                                </div>
                                <!--end col-->
                                <div class="col-xl-5 col-5">
                                    <p class="text-muted mb-2 fw-medium">Total Amount</p>
                                    <h5 class="fs-14 mb-0">₱<span id="total-amount">
                                            {{ number_format($event_registration->transaction->total_amount, 2) }}</span>
                                    </h5>
                                </div>
                                <!--end col-->
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="py-2 px-3">
                                <h5 class="fw-bold">General Information</h5>
                                <div class="row my-3 mt-3 gap-3">
                                    <div class="col-lg-5">
                                        <h6 class="text-muted" style="line-height: 10px;">Full Name</h6>
                                        <h6 class="fw-semibold">{{ $event_registration->user->first_name }}
                                            {{ $event_registration->user->last_name }}</h6>
                                    </div>
                                    <div class="col-lg-5">
                                        <h6 class="text-muted" style="line-height: 10px;">Contact Number</h6>
                                        <h6 class="fw-semibold">
                                            {{ $event_registration->user->contact_number ?? 'Not Found' }}</h6>
                                    </div>
                                    <div class="col-lg-5">
                                        <h6 class="text-muted" style="line-height: 10px;">Email</h6>
                                        <h6 class="fw-semibold">{{ $event_registration->user->email ?? 'Not Found' }}</h6>
                                    </div>
                                    <div class="col-lg-5">
                                        <h6 class="text-muted" style="line-height: 10px;">Address</h6>
                                        <h6 class="fw-semibold">
                                            {{ $event_registration->user->user_details->address ?? 'Not Found' }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
