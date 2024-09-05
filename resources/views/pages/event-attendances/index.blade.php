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
                            <input type="text" id="search-field" class="form-control search" placeholder="Search user name">
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
                                    <th scope="col">Event</th>
                                    <th scope="col">User</th>
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
                    <div class="modal-header p-3 bg-success-subtle">
                        <h5 class="modal-title" id="createProjectModalLabel">MFC Members</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" id="addProjectBtn-close" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <label for="projectname-input" class="form-label">MFC ID Number</label>
                        <div class="input-group">
                            <input type="text" class="form-control" aria-label="Recipient's username" aria-describedby="button-addon2">
                            <button class="btn btn-outline-success" type="button" id="button-addon2">Button</button>
                        </div>
                        <form action="#" autocomplete="off" class="createProject-form">
                            <div class="mb-4">
                                <input type="text" class="form-control" id="projectname-input" autocomplete="off" placeholder="Enter MFC ID" required>
                                <div class="invalid-feedback">Please enter a MFC ID</div>
                                <input type="hidden" class="form-control" id="projectid-input" value="" placeholder="Enter project name">
                            </div>
                            
                            <div class="hstack gap-2 justify-content-end">
                                <button type="submit" class="btn btn-primary" id="addNewProject">Search</button>
                            </div>
                        </form>
                        <div class="search-result mb-4">
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
        })
    </script>

    <script>
        $(document).ready(function () {
            // make sure that the table is loaded correctly
            $('#attendance_datatables').on('draw.dt', function() {
                $('[data-bs-toggle="tooltip"]').tooltip();
            });

            function initializeTables() {
                let columns = [{
                        data: "event",
                        name: "event",
                    },
                    {
                        data: "user",
                        name: "user",
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
