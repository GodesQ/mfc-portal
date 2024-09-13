@extends('layouts.master')
@section('title')
    @lang('translation.starter')
@endsection
@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Directory
        @endslot
        @slot('title')
            {{ $breadcrumb }}
        @endslot
    @endcomponent

    <div class="row mt-3">
        <input type="text" hidden id="section" value="{{ $section }}">
        <div class="col-lg-12">
            <div class="card-header border-0 mb-3">
                <div class="d-flex align-items-center justify-content-end">
                    <div class="flex-shrink-0">
                        <div class="d-flex flex-wrap gap-2">
                            <button type="button" class="btn {{ $btn_color }} add-btn" data-bs-toggle="modal"
                                data-bs-target="#addmemberModal">
                                <i class="ri-add-line align-bottom me-1"></i>@lang('translation.add_new_user')</button>
                            <button class="btn btn-soft-danger" id="remove-actions"><i
                                    class="ri-delete-bin-2-line"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card" id="ticketsList">
                <div class="card-body">
                    <table id="users_datatables" class="table nowrap align-middle" style="width:100%">
                        <thead>
                            <tr>
                                <th class="" data-sort="id">ID</th>
                                <th class="" data-sort="name">Name</th>
                                <th class="" data-sort="email">Email</th>
                                <th class="" data-sort="contact_number">Contact Number</th>
                                <th class="" data-sort="area">Area</th>
                                <th class="" data-sort="chapter">Chapter</th>
                                <th class="" data-sort="section">Section</th>
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

    <!-- Create Modal -->
    @component('components.new-user-modal')
        @slot('color')
            {{ $btn_color }}
        @endslot
        @slot('route')
            {{ route('users.store') }}
        @endslot
    @endcomponent

    <!-- Edit Modal -->
    @component('components.users.edit-form', [
        'sections' => $sections, 
        'roles' => $roles
    ])@endcomponent

@endsection
@section('script')
    <script>
        $(document).ready(function() {
            var editUserModal = new bootstrap.Modal(document.getElementById('user-modal'), {
                keyboard: false
            });

            // make sure that the table is loaded correctly
            $('#users_datatables').on('draw.dt', function() {
                $('[data-bs-toggle="tooltip"]').tooltip();

                $('.remove-btn').click(function() {
                    var id = $(this).attr('id');

                    showDeleteMessage({
                        message: '<strong class="text-danger">Removing this user</strong> will remove all of the information from our database.',
                        deleteFunction: function() {
                            $.ajax({
                                url: '/kids/' + id,
                                type: 'DELETE',
                                data: {
                                    _token: $('meta[name="csrf-token"]').attr(
                                        'content')
                                },
                                success: function(response) {
                                    showSuccessMessage(response.message);
                                },
                                error: function(xhr, response, error) {
                                    showErrorMessage(xhr.statusText);
                                }
                            });
                        }
                    });
                });

                // Click Edit Btn
                $('.edit-btn').click(function (e) {
                    var id = $(this).attr('id');
                    editUserModal.show();

                    $.ajax({
                        url: `/dashboard/users/${id}`,
                        method: "GET",
                        success: function(response) {
                            displayUserValues(response.user);
                        }
                    })
                })

                function displayUserValues(user) {
                    document.getElementById("user-id-field-edit").value = user.id;
                    document.getElementById("first-name-field-edit").value = user.first_name;
                    document.getElementById("last-name-field-edit").value = user.last_name;
                    document.getElementById("username-field-edit").value = user.username;
                    document.getElementById("email-field-edit").value = user.email;
                    document.getElementById("contact-number-field-edit").value = user.contact_number;
                    document.getElementById("section-field-edit").value = user.section_id;
                    document.getElementById("role-field-edit").value = user.role_id;
                }
            });

            function initializeTables() {
                let columns = [{
                        data: "id",
                        name: "id",
                    },
                    {
                        data: "name",
                        name: "name",
                        render: function(data) {
                            if (data == null) {
                                return '<span class="text-capitalize">N/A</span>';
                            }

                            return '<span class="text-capitalize">' + data + '</span>';;
                        }
                    },
                    {
                        data: "email",
                        name: "email",
                        render: function(data) {
                            if (data == null) {
                                return '<span class="text-capitalize">N/A</span>';
                            }

                            return '<span>' + data + '</span>';;
                        }
                    },
                    {
                        data: "contact_number",
                        name: "contact_number",
                        render: function(data) {
                            if (data == null) {
                                return '<span class="text-capitalize">N/A</span>';
                            }

                            return '<span class="text-capitalize d-flex">0' + data + '</span>';;
                        }
                    },
                    {
                        data: "area",
                        name: "area",
                        render: function(data) {
                            if (data == null) {
                                return '<span class="text-capitalize">N/A</span>';
                            }

                            return '<span class="text-capitalize">' + data + '</span>';;
                        }
                    },
                    {
                        data: "chapter",
                        name: "chapter",
                        render: function(data) {
                            if (data == null) {
                                return '<span class="text-capitalize">N/A</span>';
                            }

                            return '<span class="text-capitalize">' + data + '</span>';;
                        }
                    },
                    {
                        data: "section",
                        name: "section",
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: "status",
                        name: "status",
                        render: function(data, type, row) {
                            if (data == 'Active') {
                                return '<span class="text-uppercase badge bg-success-subtle text-success">' +
                                    data + '</span>';
                            } else {
                                return '<span class="text-uppercase badge bg-warning-subtle text-warning">' +
                                    data + '</span>';
                            }
                        }
                    },
                    {
                        data: "actions",
                        name: "actions",
                        orderable: false,
                        searchable: false,
                    },
                ];

                const section = $('#section').val();

                let table = $("#users_datatables").DataTable({
                    processing: true,
                    pageLength: 10,
                    responsive: true,
                    serverSide: true,
                    ajax: {
                        url: "/dashboard/directory/" + section,
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
