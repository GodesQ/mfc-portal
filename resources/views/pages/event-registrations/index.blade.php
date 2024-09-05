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
            background-color: rgb(116, 2, 2);
            border-color: rgb(116, 2, 2);
        }
    </style>

    <div class="row my-3">
        <div class="col-lg-12">
            <div class="card-header border-0 mb-3">
                <div class="d-flex align-items-center justify-content-end">
                    <div class="flex-shrink-0">
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('events.register', $event->id) }}"
                                class="btn btn-primary add-btn text-capitalize">
                                <i class="ri-add-line align-bottom me-1"></i>Register User</a>
                            <button class="btn btn-soft-danger" id="remove-actions"><i
                                    class="ri-delete-bin-2-line"></i></button>
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
        </div>
    </div>
@endsection

@section('script')
    <script>
        // make sure that the table is loaded correctly
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
                                $('#event_registrations_datatable').DataTable().draw(
                                    false);
                            },
                            error: function(xhr, response, error) {
                                showErrorMessage(xhr.statusText);
                            }
                        });
                    }
                });
            });

            $('.attendance-checkbox').click(function (e) {
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
                    success: function (response) {
                        toastr.success(response.message, "Success");
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        alert("some error");
                        console.log(XMLHttpRequest);
                    }
                })
            })
        });

        function initializeTables() {
            let columns = [{
                    data: "id",
                    name: "id",
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
