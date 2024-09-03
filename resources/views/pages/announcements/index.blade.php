@extends('layouts.master')
@section('title')
    @lang('translation.announcements')
@endsection
@section('content')
    @component('components.breadcrumb')
        @slot('title')
            @lang('translation.announcements')
        @endslot
        @slot('li_1')
            Home
        @endslot
    @endcomponent

    <div class="row mt-3">
        <div class="col-lg-12">
            <div class="card-header border-0 mb-3">
                <div class="d-flex align-items-center justify-content-end">
                    <div class="flex-shrink-0">
                        <div class="d-flex flex-wrap gap-2">
                            <button type="button" class="btn btn-primary add-btn" data-bs-toggle="modal"
                                data-bs-target="#createModal">
                                <i class="ri-add-line align-bottom me-1"></i>@lang('translation.create_announcement')</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card" id="ticketsList">
                <div class="card-body">
                    <table id="announcement_datatables" class="table nowrap align-middle" style="width:100%">
                        <thead>
                            <tr>
                                <th class="sort" data-sort="id">ID</th>
                                <th class="sort" data-sort="tasks_name">Title</th>
                                <th class="sort" data-sort="assignedto">Assigned To</th>
                                <th class="sort" data-sort="create_date">Create Date</th>
                                <th class="sort" data-sort="status">Status</th>
                                <th class="sort" data-sort="action">Actions</th>
                            </tr>
                        </thead>
                    </table>

                </div>
                <!--end card-body-->
            </div>
            <!--end card-->
        </div>
        <!--end col-->
    </div>
    <!--end row-->

    <!-- Create Announcement Modal -->
    <div class="modal fade zoomIn" id="createModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0">
                <div class="modal-header p-3 bg-info-subtle">
                    <h5 class="modal-title" id="exampleModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        id="close-modal"></button>
                </div>
                <form class="announcements_tablelist_form" autocomplete="off" action="{{ route('announcements.store') }}"
                    method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-lg-12">
                                <div>
                                    <label for="announcementTitle-field" class="form-label">What?</label>
                                    <input name="title" type="text" id="announcementTitle-field" class="form-control"
                                        placeholder="Title" required />
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label">Content</label>
                                    <textarea name="content" class="ckeditor-classic"></textarea>
                                </div>
                            </div>
                            <div class="col-lg-6 d-none" id="who_group">
                                <label for="service" class="form-label">Who?</label>
                                <select class="form-control" data-plugin="choices" name="service" id="service"
                                    data-choices>
                                    <option value="">Service</option>
                                    @foreach ($services as $service)
                                        <option value="{{ $service->id }}">{{ $service->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-6 d-none" id="who_group">
                                <label for="section" class="form-label" style="color: transparent;">x</label>
                                <select class="form-control" data-plugin="choices" name="section" id="section"
                                    data-choices data-choices-search-false data-choices-removeItem>
                                    <option value="">Section</option>
                                    <option value="1">Kids</option>
                                    <option value="2">Youth</option>
                                    <option value="3">Singles</option>
                                    <option value="4">Handmaids</option>
                                    <option value="5">Servants</option>
                                    <option value="6">Couples</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-check form-check-success mt-4">
                            <input name="sent_to_all" class="form-check-input" type="checkbox" id="send_to_all"
                                checked="">
                            <label class="form-check-label" for="send_to_all">
                                Send to all?
                            </label>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <div class="hstack gap-2 justify-content-end">
                            <button type="button" class="btn btn-light"
                                data-bs-dismiss="modal">@lang('translation.close')</button>
                            <button type="submit" class="btn btn-success" id="">@lang('translation.create_announcement')</button>
                            {{-- <button type="button" class="btn btn-success" id="edit-btn">Update</button> --}}
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ URL::asset('build/js/app.js') }}"></script>

    <script>
        $(document).ready(function() {
            var announcements = @json($announcements);

            if (announcements.length <= 0) {
                $('.noresult').css('display', 'block');
                $('.pagination_div').addClass('d-none');
            }

            $('#send_to_all').on('change', function() {
                var group = $('div#who_group');
                if ($(this).is(':checked')) {
                    group.addClass('d-none');
                    $('#service').attr('required', false);
                } else {
                    group.removeClass('d-none');
                    $('#service').attr('required', true);
                }
            });

            // make sure that the table is loaded correctly
            $('#announcement_datatables').on('draw.dt', function() {
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
                                    $('#announcement_datatables').DataTable().draw(false);
                                },
                                error: function(xhr, response, error) {
                                    showErrorMessage(xhr.statusText);
                                }
                            });
                        }
                    });
                });
            });

            function initializeTables() {
                let columns = [{
                        data: "id",
                        name: "id",
                    },
                    {
                        data: "title",
                        name: "title",
                        render: function(data) {
                            return '<span class="text-capitalize">' + data + '</span>'
                        }
                    },
                    {
                        data: "section_id",
                        name: "section_id",
                    },
                    {
                        data: "created_at",
                        name: "created_at",
                        render: function(data, type, row) {
                            // Format the 'created_at' date using moment.js or any other library
                            if (type === "display" && data !== null) {
                                return moment(data).format(
                                    "MM-DD-YYYY"); // Replace with your desired format
                            }
                            return data; // Return the original data for sorting and other types
                        },
                    },
                    {
                        data: "status",
                        name: "status",
                        render: function(data, type, row) {
                            if (data == 'shown') {
                                var status =
                                    '<span class="text-uppercase badge bg-success-subtle text-success">' +
                                    data + '</span>';
                            } else {
                                var status =
                                    '<span class="text-uppercase badge bg-warning-subtle text-warning">' +
                                    data + '</span>';
                            }
                            return status;
                        }
                    },
                    {
                        data: "actions",
                        name: "actions",
                        orderable: false,
                        searchable: false,
                    },
                ];

                let table = $("#announcement_datatables").DataTable({
                    processing: true,
                    pageLength: 10,
                    responsive: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('announcements.index') }}",
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
        });
    </script>
@endsection
