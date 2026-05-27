@extends('layouts.master')
@section('title')
    @lang('translation.events')
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Event Registration
        @endslot
        @slot('title')
            {{ $endPoint }}
        @endslot
    @endcomponent

    <div class="row">
        <form action="{{ route('events.register.post') }}" method="POST" id="register-form">
            @csrf
            <input type="hidden" name="event_id" id="event-id-field" value="{{ $event->id }}">
            <input type="hidden" id="event-early-bird-discount-field" value="{{ $event->early_bird_discount }}">
            <input type="hidden" id="event-early-bird-enabled-field"
                value="{{ $event->is_early_bird_enabled ? 1 : 0 }}">
            @php
                $availableTicketIds = $event->tickets
                    ->filter(fn ($ticket) => $ticket->is_unlimited || (int) $ticket->total_number_of_tickets - (int) $ticket->event_registrations_count > 0)
                    ->pluck('id');
                $selectedTicketId = old('ticket_id');

                if (! $selectedTicketId || ! $availableTicketIds->contains((int) $selectedTicketId)) {
                    $selectedTicketId = $availableTicketIds->first();
                }
            @endphp
            <div class="row mb-3">
                <div class="col-xl-8">
                    <div class="card">
                        <div class="card-header border-bottom-dashed">
                            <h5 class="card-title mb-0">Select Ticket</h5>
                        </div>
                        <div class="card-body">
                            @forelse ($event->tickets as $ticket)
                                @php
                                    $usedTickets = (int) $ticket->event_registrations_count;
                                    $remainingTickets = $ticket->is_unlimited
                                        ? null
                                        : max((int) $ticket->total_number_of_tickets - $usedTickets, 0);
                                    $isSoldOut = ! $ticket->is_unlimited && $remainingTickets <= 0;
                                    $ticketPrice = $ticket->is_free ? 0.00 : (float) $ticket->price;
                                @endphp
                                <label class="border rounded p-3 d-block mb-3 @if ($isSoldOut) bg-light text-muted @endif"
                                    for="ticket-{{ $ticket->id }}">
                                    <div class="form-check">
                                        <input class="form-check-input ticket-option" type="radio" name="ticket_id"
                                            id="ticket-{{ $ticket->id }}" value="{{ $ticket->id }}"
                                            data-price="{{ $ticketPrice }}"
                                            data-name="{{ $ticket->ticket_name }}"
                                            data-remaining="{{ $ticket->is_unlimited ? '' : $remainingTickets }}"
                                            @checked((int) $selectedTicketId === $ticket->id)
                                            @disabled($isSoldOut)>
                                        <div class="form-check-label w-100">
                                            <div class="d-flex justify-content-between gap-3">
                                                <div>
                                                    <h6 class="mb-1">{{ $ticket->ticket_name }}</h6>
                                                    @if ($ticket->description)
                                                        <div class="text-muted">{!! nl2br(e($ticket->description)) !!}</div>
                                                    @endif
                                                </div>
                                                <div class="text-end flex-shrink-0">
                                                    <div class="fw-semibold">
                                                        @if ($ticket->is_free || (float) $ticket->price <= 0)
                                                            Free
                                                        @else
                                                            ₱ {{ number_format($ticket->price, 2) }}
                                                        @endif
                                                    </div>
                                                    <small class="{{ $isSoldOut ? 'text-danger' : 'text-muted' }}">
                                                        @if ($ticket->is_unlimited)
                                                            Unlimited
                                                        @elseif ($isSoldOut)
                                                            Sold out
                                                        @else
                                                            {{ $remainingTickets }} left
                                                        @endif
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            @empty
                                <div class="alert alert-warning mb-0">
                                    Registration is unavailable until tickets are configured for this event.
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="row g-2">
                                <div class="col-xl-6">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="mfc-user-id-input"
                                            aria-label="Example text with two button addons" placeholder="Search MFC ID">
                                        <button type="button" class="btn btn-primary" id="search-mfc-user-btn" data-bs-toggle="modal"
                                            data-bs-target=".user-modal" @disabled($availableTicketIds->isEmpty())>Search</button>
                                    </div>
                                    <div class="modal fade user-modal" tabindex="-1" role="dialog"
                                        aria-labelledby="mySmallModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                    <div class="mfc-members"></div>
                                                    <div class="text-center my-2">
                                                        <button type="button" class="btn btn-light"
                                                            data-bs-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div><!-- /.modal-content -->
                                        </div><!-- /.modal-dialog -->
                                    </div><!-- /.modal -->
                                </div>
                                <!--end col-->
                                <div class="col-sm-auto ms-auto">
                                    <div class="list-grid-nav hstack gap-1">

                                        <button type="button" class="btn btn-primary" id="register-self-btn"
                                            data-mfc-id="{{ auth()->user()->mfc_id_number }}"
                                            data-user-id="{{ auth()->user()->id }}" @disabled($availableTicketIds->isEmpty())>
                                            I will register myself <i class="ri-add-fill me-1 align-bottom"></i>
                                        </button>
                                    </div>
                                </div>
                                <!--end col-->
                            </div>
                            <!--end row-->
                        </div>
                    </div>
                    {{-- <input type="hidden"> --}}
                    <div class="products"></div>

                    <div class="text-end mb-4">
                        <button type="submit" class="btn btn-success btn-label right ms-auto" id="register-submit-btn"
                            @disabled($availableTicketIds->isEmpty())>
                            <i class="ri-arrow-right-line label-icon align-bottom fs-16 ms-2"></i> Register
                        </button>
                    </div>
                </div>
                <!-- end col -->

                <div class="col-xl-4">
                    <div class="sticky-side-div">
                        <div class="card">
                            <div class="card-header border-bottom-dashed">
                                <h5 class="card-title mb-0">Order Summary</h5>
                            </div>
                            <div class="card-header bg-light-subtle border-bottom-dashed">
                                <div class="text-left">
                                    <h6 class="mb-2">Do you want to donate?</h6>
                                </div>
                                <div class="hstack gap-3 px-3 mx-n3">
                                    <div class="col-lg-12">
                                        <div class="input-group">
                                            <span class="input-group-text">₱</span>
                                            <input class="form-control me-auto" value="0" type="number"
                                                name="donation" id="donation-field" placeholder="Enter donation amount"
                                                aria-label="Add donation amount here...">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body pt-2">
                                <div class="table-responsive">
                                    <table class="table table-borderless mb-0">
                                        <tbody>
                                            <tr>
                                                <td>Selected Ticket :</td>
                                                <td class="text-end" id="registration-ticket">None</td>
                                            </tr>
                                            <tr>
                                                <td>Sub Total :</td>
                                                <td class="text-end" id="registration-subtotal">₱ 0.00</td>
                                            </tr>
                                            <tr id="registration-early-bird-row" class="d-none">
                                                <td>Early Bird Discount :</td>
                                                <td class="text-end text-success" id="registration-early-bird-discount">
                                                    ₱ 0.00</td>
                                            </tr>
                                            <tr>
                                                <td>Donation : </td>
                                                <td class="text-end" id="registration-donation">₱ 0.00</td>
                                            </tr>
                                            <tr>
                                                <td>Convenience Fee : </td>
                                                <td class="text-end" id="registration-convenience-fee">0.00</td>
                                            </tr>
                                            <tr>
                                                <td>Number of Registrations : </td>
                                                <td class="text-end" id="registration-pax">0 x</td>
                                            </tr>
                                            <tr class="table-active">
                                                <th>Total (Peso) :</th>
                                                <td class="text-end">
                                                    <span class="fw-semibold" id="registration-total">
                                                        ₱ 0.00
                                                    </span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- end table-responsive -->
                            </div>
                        </div>
                    </div>

                    <div class="sticky-side-div">
                        <div class="card">
                            <div class="card-header border-bottom-dashed">
                                <h5 class="card-title mb-0">Event Details</h5>
                            </div>
                            <div class="card-header bg-light-subtle border-bottom-dashed">
                                <h3>{{ $event->title }}</h3>
                                <div class="flex gap-2">
                                    <span class="bg-primary badge text-uppercase">Worldwide</span>
                                    <span class="badge bg-primary text-uppercase"></span>
                                </div>
                                <div class="my-2">
                                    {!! $event->description !!}
                                </div>
                                {{-- <div class="my-1">
                                <img src="{{ URL::asset('uploads/' . $event->poster) }}" alt="" class="rounded"
                                    style="width: 100%;">
                            </div> --}}
                            </div>
                            <div class="card-body pt-2">
                                <div class="table-responsive">
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
                                                    Ticket based
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- end table-responsive -->
                            </div>
                        </div>
                    </div>
                    <!-- end stickey -->

                </div>
            </div>
        </form>
    </div>
@endsection

@section('script')
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <script>
                toastr.error("{{ $error }}", "Failed");
            </script>
        @endforeach
    @endif
    <script>
        let searchBtn = document.querySelector('#search-mfc-user-btn');
        let ticketOptions = document.querySelectorAll('.ticket-option');

        function getSelectedTicket() {
            return document.querySelector('.ticket-option:checked');
        }

        function getSelectedTicketPrice() {
            const selectedTicket = getSelectedTicket();
            return selectedTicket ? parseFloat(selectedTicket.dataset.price || '0') || 0 : 0;
        }

        function getSelectedTicketRemaining() {
            const selectedTicket = getSelectedTicket();

            if (!selectedTicket || selectedTicket.dataset.remaining === '') {
                return null;
            }

            return parseInt(selectedTicket.dataset.remaining, 10);
        }

        searchBtn.addEventListener('click', function(e) {
            let searchInput = document.querySelector('#mfc-user-id-input');
            let mfcMembersDiv = document.querySelector('.mfc-members');

            if (!searchInput.value) return toastr.warning("Please input the search field.");

            mfcMembersDiv.innerHTML = "";

            $.ajax({
                method: "GET",
                url: `/dashboard/users/search?mfc_user_id=${searchInput.value}`,
                success: function(response) {
                    let users = response.users;
                    if (users.length < 1) return [];

                    let output = "";
                    users.forEach(user => {
                        output += `<div class="d-flex justify-content-center align-items-center gap-4 border py-3">
                                    <div style="width: 20%;">
                                        <img class="rounded-circle" src="{{ URL::asset('uploads/avatars/${user.avatar}') }}" style="width: 100%;">
                                    </div>
                                    <div style="width: 70%;">
                                        <h5>${user.first_name} ${user.last_name}</h5>
                                        <h6>${user.mfc_id_number}</h6>
                                        <button type="button" class="btn btn-primary btn-sm" data-mfc-id="${user.mfc_id_number}" data-user-id="${user.id}" id="register-user-btn">
                                            Confirm
                                        </button>
                                    </div>
                                </div>`;
                    });

                    mfcMembersDiv.innerHTML = output;

                    let registerUserBtn = document.querySelector("#register-user-btn");
                    registerUserBtn.addEventListener('click', registerUser);
                }
            })
        })

        function userExists(user_id) {
            let user_ids = document.querySelectorAll(".user-id");
            return Array.from(user_ids).some(input => input.value == user_id);
        }

        function handleUserEventRegistration(mfc_id_number, user_id) {
            let selectedTicket = getSelectedTicket();

            if (!selectedTicket) {
                return toastr.warning("Please select a ticket first.");
            }

            let remainingTickets = getSelectedTicketRemaining();
            let currentUserCount = document.querySelectorAll(".user-id").length;

            if (remainingTickets !== null && currentUserCount >= remainingTickets) {
                return toastr.warning("The selected ticket does not have enough available slots.");
            }

            if (!userExists(user_id)) {
                displayMember(mfc_id_number);
            } else {
                toastr.warning("User already exists in the list.");
            }
        }

        $('#register-self-btn').click(function() {
            let mfc_id_number = $(this).data('mfc-id');
            let user_id = $(this).data('user-id');
            handleUserEventRegistration(mfc_id_number, user_id);
        });

        function registerUser(e) {
            let mfc_id_number = e.target.getAttribute('data-mfc-id');
            let user_id = e.target.getAttribute('data-user-id');
            handleUserEventRegistration(mfc_id_number, user_id);
        }

        function displayMember(mfc_id_number) {
            let productsDiv = document.querySelector('.products');

            $.ajax({
                method: "GET",
                url: `/dashboard/users/search?mfc_user_id=${mfc_id_number}`,
                success: function(response) {
                    $('.user-modal').modal('hide');

                    let users = response.users;
                    if (users.length < 1) return [];

                    let output = "";
                    users.forEach(user => {
                        output += `<div class="card product">
                                        <div class="card-body">
                                            <div class="row gy-3">
                                                <div class="col-sm-auto">
                                                    <div class="avatar-lg bg-light rounded p-1">
                                                        <img src="{{ URL::asset('uploads/avatars/${user.avatar}') }}" alt="" class="img-fluid d-block">
                                                    </div>
                                                </div>
                                                <div class="col-sm">
                                                    <h5 class="fs-14 text-truncate"><a href="ecommerce-product-detail.html"
                                                            class="text-body">${user.first_name} ${user.last_name}</a></h5>
                                                    <ul class="list-inline text-muted">
                                                        <li>Email : <span class="fw-medium">${user.email}</span></li>
                                                        <li>MFC User ID : <span class="fw-medium">${user.mfc_id_number}</span></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <div class="row align-items-center gy-3">
                                                <div class="col-sm">
                                                    <div class="d-flex flex-wrap my-n1">
                                                        <input type="hidden" name="users[]" class="user-id" value="${user.id}" />
                                                        <div>
                                                            <a href="#" class="d-block text-body p-1 px-2 remove-btn" data-bs-toggle="modal"
                                                                data-bs-target="#removeItemModal"><i
                                                                    class="ri-delete-bin-fill text-muted align-bottom me-1"></i> Remove</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>`;
                    });

                    productsDiv.insertAdjacentHTML('beforeend', output);

                    document.querySelectorAll('.remove-btn').forEach(button => {
                        button.addEventListener('click', function(event) {
                            event.preventDefault();
                            const card = this.closest('.product');
                            if (card) {
                                card.remove(); // Remove the parent card element
                                computeTotalAmount();
                            }
                        });
                    });

                    computeTotalAmount();
                }
            })

        }

        function computeTotalAmount() {
            let user_ids = document.querySelectorAll(".user-id");
            let selectedTicket = getSelectedTicket();
            let ticketPrice = getSelectedTicketPrice();
            let earlyBirdDiscount = parseFloat(document.querySelector('#event-early-bird-discount-field').value || '0') || 0;
            let isEarlyBirdEnabled = document.querySelector('#event-early-bird-enabled-field').value === '1';
            let donation_amount = parseFloat(document.getElementById("donation-field").value || '0') || 0;
            let convenience_fee = 10.00;
            let subtotal = ticketPrice * user_ids.length;
            let appliedEarlyBirdDiscount = isEarlyBirdEnabled && ticketPrice > 0 && user_ids.length > 0 ?
                Math.min(earlyBirdDiscount, ticketPrice) : 0;

            let total_convenience_fee = 0;

            for (let index = 0; index < user_ids.length; index++) {
                total_convenience_fee += convenience_fee;
            }

            let totalAmount = subtotal - appliedEarlyBirdDiscount + total_convenience_fee + parseFloat(donation_amount);

            $('#registration-ticket').text(selectedTicket ? selectedTicket.dataset.name : 'None');
            $('#registration-subtotal').html(`₱ ${subtotal.toFixed(2)}`);
            $('#registration-early-bird-discount').html(`₱ ${appliedEarlyBirdDiscount.toFixed(2)}`);
            $('#registration-early-bird-row').toggleClass('d-none', appliedEarlyBirdDiscount <= 0);
            $('#registration-convenience-fee').html(`₱ ${total_convenience_fee.toFixed(2)}`);
            $('#registration-pax').html(`${user_ids.length} x`);
            $('#registration-total').html(`₱ ${totalAmount.toFixed(2)}`);
        }

        document.getElementById("donation-field").addEventListener('input', (e) => {
            if (e.target.value == '') e.target.value = "0";

            let registration_donation_text = document.querySelector('#registration-donation');
            registration_donation_text.innerHTML = `₱ ${parseFloat(e.target.value).toFixed(2)}`;


            computeTotalAmount();
        });

        ticketOptions.forEach(option => {
            option.addEventListener('change', computeTotalAmount);
        });

        document.getElementById("register-form").addEventListener('submit', function(e) {
            let userCount = document.querySelectorAll(".user-id").length;
            let selectedTicket = getSelectedTicket();
            let remainingTickets = getSelectedTicketRemaining();

            if (!selectedTicket) {
                e.preventDefault();
                return toastr.warning("Please select a ticket.");
            }

            if (userCount < 1) {
                e.preventDefault();
                return toastr.warning("Please add at least one attendee.");
            }

            if (remainingTickets !== null && userCount > remainingTickets) {
                e.preventDefault();
                return toastr.warning("The selected ticket does not have enough available slots.");
            }
        });

        computeTotalAmount();
    </script>
@endsection
