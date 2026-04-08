@extends('layouts.master')
@section('title')
    @lang('translation.starter')
@endsection
@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Events
        @endslot
        @slot('title')
            {{ $endPoint }}
        @endslot
    @endcomponent

    <div class="row mt-3">
        <div class="col-lg-12">
            <div class="card-header border-0 mb-3">
                <div class="d-flex align-items-center justify-content-end">
                    <div class="flex-shrink-0">
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('events.create') }}" class="btn btn-primary add-btn text-capitalize">
                                <i class="ri-add-line align-bottom me-1"></i>add new event</a>
                            <button class="btn btn-soft-danger" id="remove-actions"><i
                                    class="ri-delete-bin-2-line"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card" id="ticketsList">
                <div class="card-body">
                    <table id="events_datatable" class="table nowrap align-middle table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th class="" data-sort="id">ID</th>
                                <th class="" data-sort="title">Title</th>
                                <th class="" data-sort="gender">Date</th>
                                <th class="" data-sort="status">Section</th>
                                <th class="" data-sort="status">Status</th>
                                <th class="" data-sort="action">Actions</th>
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

@endsection
@section('script')
    <script src="{{ URL::asset('build/js/app.js') }}"></script>

    <script>
        $(document).ready(function() {
            // make sure that the table is loaded correctly
            $('#events_datatable').on('draw.dt', function() {

                $('[data-bs-toggle="tooltip"]').tooltip();

                $('.remove-btn').click(function() {
                    var id = $(this).attr('id');

                    showDeleteMessage({
                        message: '<strong class="text-danger">Removing this event</strong> will remove all of the information from our database.',
                        deleteFunction: function() {
                            $.ajax({
                                url: `/dashboard/events/${id}`,
                                method: "DELETE",
                                data: {
                                    id: id,
                                    _token: "{{ csrf_token() }}"
                                },
                                success: function(response) {
                                    showSuccessMessage(response.message);
                                    $('#events_datatable').DataTable().ajax
                                        .reload(null,
                                            false
                                        ); // false to keep the current page
                                },
                                error: function(xhr, status, error) {
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
                            if (data == null) {
                                return '<span class="text-capitalize">N/A</span>';
                            }

                            return '<span class="text-capitalize">' + data + '</span>';;
                        }
                    },
                    {
                        data: "start_date",
                        name: "start_date",
                        render: function(data) {
                            if (data == null) {
                                return '<span class="text-capitalize">N/A</span>';
                            }

                            return '<span class="text-capitalize">' + data + '</span>';
                        }
                    },
                    {
                        data: "section",
                        name: "section",
                    },
                    {
                        data: "status",
                        name: "status",
                    },
                    {
                        data: "actions",
                        name: "actions",
                        orderable: false,
                        searchable: false,
                    },
                ];

                let table = $("#events_datatable").DataTable({
                    processing: true,
                    pageLength: 10,
                    responsive: true,
                    serverSide: true,
                    ajax: {
                        url: "/dashboard/events",
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    },
                    columns: columns,
                    language: {
                        emptyTable: `<div class="noresult">
                                        <div class="text-center">
                                            <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop" id="search-icon"
                                                colors="primary:#121331,secondary:#08a88a" style="width:75px;height:75px">
                                            </lord-icon>
                                            <h5 class="mt-2">Sorry! This table is empty</h5>
                                            <p class="text-muted mb-0">No data available in table. Please add some records.</p>
                                        </div>
                                    </div>`,
                        zeroRecords: `<div class="noresult">
                                        <div class="text-center">
                                            <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop" id="search-icon"
                                                colors="primary:#b4b4b4,secondary:#08a88a" style="width:75px;height:75px">
                                            </lord-icon>
                                            <h5 class="mt-2">Sorry! No Result Found</h5>
                                            <p class="text-muted mb-0">We've searched all of our records, We did not find any data for you search.</p>
                                        </div>
                                    </div>`,
                    },
                    order: [
                        [0, "desc"],
                    ],
                });

            }

            initializeTables();
        })
    </script>
@endsection
