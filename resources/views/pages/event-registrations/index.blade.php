@extends('layouts.master')

@section('title')
    @lang('translation.event_registrations')
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Events
        @endslot
        @slot('title')
            Registrations
        @endslot
    @endcomponent

    <style>
        .attendance-checkbox {
            text-align: center;
            width: 40px;
            height: 20px;
            cursor: pointer;
            accent-color: #3e5287;
        }

        .form-switch-success .form-check-input {
            background-color: #fee8e2;
            border-color: #fa7d5b;
        }
    </style>

    <div class="row my-3">
        <div class="col-lg-12">
            <div class="card-header border-0 mb-3">
                <div class="d-flex align-items-center justify-content-end">
                    <div class="flex-shrink-0">
                        <div class="d-flex flex-wrap gap-2">
                            <button class="btn btn-outline-dark" id="refresh-btn">Refresh <i class="ri-restart-line"></i></button>
                            <a href="{{ route('events.register', $event->id) }}"
                                class="btn btn-primary add-btn text-capitalize">
                                <i class="ri-add-line align-bottom me-1"></i>Register User</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <table id="event_registrations_datatable" class="table nowrap align-middle table-striped"
                        style="width:100%">
                        <thead>
                            <tr>
                                <th class="" data-sort="id">ID</th>
                                <th class="" data-sort="registration_code">Registration Code</th>
                                <th class="" data-sort="user">User</th>
                                <th class="" data-sort="event">Event</th>
                                <th class="" data-sort="amount">Amount</th>
                                <th class="" data-sort="status">Status</th>
                                <th class="" data-sort="attendance_status">Attendance Status</th>
                                <th class="" data-sort="action">Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>

            <div class="modal fade bs-example-modal-center" tabindex="-1" role="dialog"
                aria-labelledby="mySmallModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-primary-subtle py-3">
                            <h5 class="modal-title" id="myModalLabel">QR Code</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body d-flex justify-content-center align-items-center p-5">
                            <div class="d-flex justify-content-center align-items-center flex-column" id="qrcode-loading">
                                <img src="{{ URL::asset('build/icons/qr-code.gif') }}" style="width: 100px; height: 100px;" alt="">
                                <h5 class="fw-bold">Generating QR Code...</h5>
                            </div>
                            <div class="qr-code-div"></div>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        $('#event_registrations_datatable').on('draw.dt', function() {
            $('[data-bs-toggle="tooltip"]').tooltip();

            $('.remove-btn').click(function() {
                var id = $(this).attr('id');

                showDeleteMessage({
                    message: 'Deleting this announcement will remove all of the information from our database.',
                    deleteFunction: function() {
                        $.ajax({
                            url: '/dashboard/announcements/' + id,
                            type: 'DELETE',
                            data: {
                                _token: $('meta[name="csrf-token"]').attr(
                                    'content')
                            },
                            success: function(response) {
                                showSuccessMessage(response.message);
                                $('#event_registrations_datatable').DataTable()
                                    .draw(
                                        false);
                            },
                            error: function(xhr, response, error) {
                                showErrorMessage(xhr.statusText);
                            }
                        });
                    }
                });
            });

            $('.attendance-checkbox').click(function(e) {
                let user_id = e.target.getAttribute('data-user-id');
                let event_id = e.target.getAttribute('data-event-id');
                let checked = e.target.checked ? 1 : 0;
                let token = "{{ csrf_token() }}";

                $.ajax({
                    method: "POST",
                    url: "{{ route('attendances.save') }}",
                    data: {
                        _token: token,
                        event_id: event_id,
                        user_id: user_id,
                        checked: checked,
                    },
                    success: function(response) {
                        toastr.success(response.message, "Success");
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        alert("some error");
                        console.log(XMLHttpRequest);
                    }
                })
            });


            // Listen for clicks on any button with the class 'qr-btn'
            $('.qr-btn').click(function(e) {
                // Get the registration code for the clicked button
                let registration_code = this.getAttribute('data-registration-code');
                
                // Clear any previous QR code
                let qrCodeDiv = document.querySelector('.qr-code-div');
                qrCodeDiv.innerHTML = '';
                $('#qrcode-loading').removeClass('d-none');

                // Wait for the modal to be fully shown before generating the QR code
                $('.bs-example-modal-center').one('shown.bs.modal', function() {
                    setTimeout(() => {
                        $('#qrcode-loading').addClass('d-none');
                        generateQRCode(registration_code);
                    }, 2500);
                });
            });
        });

        $('#refresh-btn').click(function () {
            $('#event_registrations_datatable').DataTable().draw(false);
        })

        const generateQRCode = (qrContent) => {
            let qrCodeDiv = document.querySelector('.qr-code-div');
            // Clear the previous QR code before generating a new one
            qrCodeDiv.innerHTML = '';

            if(qrContent == '') qrContent = qrContent;

            return new QRCode(qrCodeDiv, {
                text: qrContent,
                width: 256,
                height: 256,
                colorDark: "#000000",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.H,
            });
        }

        function initializeTables() {
            let columns = [{
                    data: "id",
                    name: "id",
                },
                {
                    data: "registration_code",
                    name: "registration_code"
                },
                {
                    data: "user",
                    name: "user",
                },
                {
                    data: "event",
                    name: "event",
                },
                {
                    data: "amount",
                    name: "amount",
                },
                {
                    data: "status",
                    name: "status",
                    orderable: false,
                    searchable: false,
                },
                {
                    data: "attendance_status",
                    name: "attendance_status",
                    orderable: false,
                    searchable: false,
                },
                {
                    data: "actions",
                    name: "actions",
                    orderable: false,
                    searchable: false,
                },
            ];

            let table = $("#event_registrations_datatable").DataTable({
                processing: true,
                pageLength: 10,
                responsive: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('events.registrations.index', $event->id) }}",
                },
                columns: columns,
                language: {
                    emptyTable: `<div class="noresult">
                        <div class="text-center">
                            <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                                colors="primary:#121331,secondary:#08a88a" style="width:75px;height:75px">
                            </lord-icon>
                            <h5 class="mt-2">Sorry! This table is empty</h5>
                            <p class="text-muted mb-0">No data available in table. Please add some records.</p>
                        </div>
                    </div>`
                },
                order: [
                    [0, "desc"], // Sort by the first column (index 0) in descending order
                ],
            });
        }

        initializeTables();
    </script>
@endsection
