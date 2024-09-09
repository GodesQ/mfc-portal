@extends('layouts.master')

@section('title')
    @lang('translation.attendance')
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Events
        @endslot
        @slot('title')
            Attendance
        @endslot
    @endcomponent

    <style>
        .file-manager-sidebar {
            min-width: 400px !important;
            max-width: 400px !important;
        }
    </style>

    <div class="chat-wrapper d-lg-flex gap-1 mx-n4 mt-n4 p-1">
        <div class="file-manager-sidebar">
            <div class="p-4 d-flex flex-column h-100">
                <div class="mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title my-2 mb-3">Search Event</h4>
                            <select name="event" id="event-select-field" class="form-select">
                                @foreach ($events as $event)
                                    <option value="{{ $event->id }}">{{ $event->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <button class="btn btn-success w-100" data-bs-target="#createProjectModal" data-bs-toggle="modal">
                        <i class="ri-add-line align-bottom"></i> Add User
                    </button>
                </div>
            </div>
        </div><!--end side content-->
        <div class="file-manager-content w-100 p-4 pb-0">
            <h5 class="fw-semibold mb-4">Attendees</h5>
            <div class="p-3 bg-light rounded mb-4">
                <div class="row g-2">
                    <div class="col-lg">
                        <div class="search-box">
                            <input type="text" id="search-field" class="form-control search" placeholder="Search MFC ID">
                            <i class="ri-search-line search-icon"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="todo-content position-relative px-4 mx-n4" id="todo-content">
                <div class="todo-task" id="todo-task">
                    <div class="table-responsive">
                        <table id="attendance_datatables" class="table align-middle position-relative table-nowrap">
                            <thead class="table-active">
                                <tr>
                                    <th scope="col">User</th>
                                    <th scope="col">Attendance Date</th>
                                    <th scope="col">Event</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>

        </div>

        <div class="modal fade zoomIn" id="createProjectModal" tabindex="-1" aria-labelledby="createProjectModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header p-3 bg-primary-subtle">
                        <h5 class="modal-title" id="createProjectModalLabel">MFC Members</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" id="addProjectBtn-close" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <label for="projectname-input" class="form-label">MFC ID Number</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="mfc-id-number-search" aria-describedby="button-addon2">
                            <button class="btn btn-outline-primary" type="button" id="search-mfc-btn">Search</button>
                        </div>
                        <div class="search-result my-4">
                            <h6>Search Result</h6>
                            <div id="mfc-member-result">No user found!</div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end modal-dialog -->
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('#event-select-field').select2();

            $('#search-mfc-btn').click(function (e) {
                let mfcIdNumber = document.querySelector('#mfc-id-number-search').value;
                if(!mfcIdNumber) return toastr.warning("No MFC ID Found");

                $.ajax({
                    method: "GET",
                    url: `/dashboard/users/search?mfc_user_id=${mfcIdNumber}`,
                    success: function (response) {
                        const users = response.users;
                        let mfc_member_result = document.querySelector('#mfc-member-result');
                        let output = "";

                        users.forEach(user => {
                            output += `<div class="my-2 border p-2 d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5>${user.first_name} ${user.last_name}</h5>
                                            <h6># ${user.mfc_id_number}</h6>
                                        </div>
                                        <button id="add-user-attendance" data-user-id="${user.id}" class="btn btn-sm btn-primary">
                                            Add User <i class="ri-add-line"></i>
                                        </button>
                                    </div>`;
                        });

                        mfc_member_result.innerHTML = output;

                        $('#add-user-attendance').click(function (e) {
                            let user_id = this.getAttribute('data-user-id');
                            let event_id = $('#event-select-field').val();

                            $.ajax({
                                method: "POST",
                                url: "{{ route('attendances.users.store') }}",
                                data: {
                                    _token: "{{ csrf_token() }}",
                                    user_id: user_id,
                                    event_id: event_id,
                                },
                                success: function (response) {
                                    toastr.success(response.message);
                                },
                                error: function (error) {
                                    let responseJSON = error.responseJSON;
                                    toastr.error(responseJSON.errors.message);
                                }
                            })
                        })
                    }
                })
            })
        })
    </script>

    <script>
        $(document).ready(function () {
            // make sure that the table is loaded correctly
            $('#attendance_datatables').on('draw.dt', function() {
                $('[data-bs-toggle="tooltip"]').tooltip();
            });

            function initializeTables() {
                let columns = [
                    {
                        data: "user",
                        name: "user",
                    },
                    {
                        data: "attendance_date",
                        name: "attendance_date"
                    },
                    {
                        data: "event",
                        name: "event",
                    },
                    {
                        data: "actions",
                        name: "actions",
                        orderable: false,
                        searchable: false,
                    },
                ];

                let table = $("#attendance_datatables").DataTable({
                    processing: true,
                    pageLength: 10,
                    lengthChange: false,
                    responsive: true,
                    serverSide: true,
                    searching: false,
                    ordering: false,
                    ajax: {
                        url: "{{ route('attendances.index') }}",
                        data: function(d) {
                            d.search = $('#search-field').val(),
                            d.event_id = $('#event-select-field').val()
                        }
                    },
                    columns: columns,
                    order: [
                        [0, "desc"], // Sort by the first column (index 0) in descending order
                    ],
                });
            }

            initializeTables();
        });

        $("#event-select-field").on('change', function(e) {
            $('#attendance_datatables').DataTable().ajax.reload(null, false);
        })
    </script>
@endsection
