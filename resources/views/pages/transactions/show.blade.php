@extends('layouts.master')

@section('title')
    Transactions
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('title')
            @lang('translation.transactions')
        @endslot
        @slot('li_1')
            Details
        @endslot
    @endcomponent

    <div class="row my-3">
        <div class="col-lg-12">
            <div class="card-header border-0 mb-3">
                <div class="d-flex align-items-center justify-content-end">
                    <div class="flex-shrink-0">
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('transactions.index') }}" class="btn btn-dark add-btn text-capitalize">
                                <i class="ri-arrow-left-line align-bottom me-1"></i>Back to List</a>
                            <button class="btn btn-soft-danger" id="remove-actions"><i
                                    class="ri-delete-bin-2-line"></i></button>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row my-2">
                <div class="col-lg-7">
                    <div class="card">
                        <div class="card-body">
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
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="card">
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-lg-4 col-6">
                                    <p class="text-muted mb-2 text-uppercase fw-semibold">Transaction Code</p>
                                    <h5 class="fs-14 mb-0"><span id="invoice-no">{{ $transaction->transaction_code }}</span>
                                    </h5>
                                </div>
                                <!--end col-->
                                <div class="col-lg-4 col-6">
                                    <p class="text-muted mb-2 text-uppercase fw-semibold">Reference Code</p>
                                    <h5 class="fs-14 mb-0"><span id="invoice-no">{{ $transaction->reference_code }}</span>
                                    </h5>
                                </div>
                                <!--end col-->
                                <div class="col-lg-4 col-6">
                                    <p class="text-muted mb-2 text-uppercase fw-semibold">Date</p>
                                    <h5 class="fs-14 mb-0">
                                        <span
                                            id="invoice-date">{{ Carbon::parse($transaction->created_at)->format('M d, Y') }}</span>
                                        <small class="text-muted"
                                            id="invoice-time">{{ Carbon::parse($transaction->created_at)->format('H:i A') }}</small>
                                    </h5>
                                </div>
                                <!--end col-->
                                <div class="col-lg-4 col-6">
                                    <p class="text-muted mb-2 text-uppercase fw-semibold">Payment Status</p>
                                    <span class="badge bg-success-subtle text-success fs-11" id="payment-status">Paid</span>
                                </div>
                                <div class="col-lg-4 col-6">
                                    <p class="text-muted mb-2 text-uppercase fw-semibold">Sub Amount</p>
                                    <h5 class="fs-14 mb-0">₱<span id="total-amount">
                                            {{ number_format($transaction->sub_amount, 2) }}</span></h5>
                                </div>
                                <!--end col-->
                                <div class="col-lg-4 col-6">
                                    <p class="text-muted mb-2 text-uppercase fw-semibold">Total Amount</p>
                                    <h5 class="fs-14 mb-0">₱<span id="total-amount">
                                            {{ number_format($transaction->total_amount, 2) }}</span></h5>
                                </div>
                                <!--end col-->
                                <div class="col-lg-4 col-6">
                                    <p class="text-muted mb-2 text-uppercase fw-semibold">Convenience Fee</p>
                                    <h5 class="fs-14 mb-0">₱<span id="total-amount">
                                            {{ number_format($transaction->convenience_fee, 2) }}</span></h5>
                                </div>
                                <!--end col-->
                                <div class="col-lg-8 col-6">
                                    <p class="text-muted mb-2 text-uppercase fw-semibold">Payment Link</p>
                                    <a href="{{ $transaction->payment_link }}" class="fs-14 mb-0">{{ $transaction->payment_link }}</a>
                                </div>
                                <!--end col-->
                            </div>
                            <!--end row-->
                        </div>
                    </div>
                    <!--end card-body-->
                </div>
            </div>
        </div>
    </div>
@endsection
