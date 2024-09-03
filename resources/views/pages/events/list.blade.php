@extends('layouts.master')
@section('title')
    @lang('translation.starter')
@endsection
@section('css')
    <link rel="stylesheet" href="{{ URL::asset('build/libs/filepond/filepond.min.css') }}" type="text/css" />
    <link rel="stylesheet"
        href="{{ URL::asset('build/libs/filepond-plugin-image-preview/filepond-plugin-image-preview.min.css') }}">
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
                            <button type="button" class="btn btn-primary add-btn text-capitalize" data-bs-toggle="modal"
                                data-bs-target="#addEventModal">
                                <i class="ri-add-line align-bottom me-1"></i>add new event</button>
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

    <!-- Create Modal -->
    @component('components.new_events_modal')
        @slot('route')
            {{ route('events.store') }}
        @endslot
    @endcomponent

    <!-- View Modal -->
    @component('components.events.edit-form', ['sections' => $sections])
        <!-- Any other slots or content you want to pass -->
    @endcomponent


@endsection
@section('script')
    <script src="{{ URL::asset('build/libs/filepond/filepond.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/filepond-plugin-image-preview/filepond-plugin-image-preview.min.js') }}">
    </script>
    <script
        src="{{ URL::asset('build/libs/filepond-plugin-file-validate-size/filepond-plugin-file-validate-size.min.js') }}">
    </script>
    <script 
        src="{{ URL::asset('build/libs/filepond-plugin-image-exif-orientation/filepond-plugin-image-exif-orientation.min.js') }}">
    </script>
    <script src="{{ URL::asset('build/libs/filepond-plugin-file-encode/filepond-plugin-file-encode.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/form-file-upload.init.js') }}"></script>
    <script src="{{ URL::asset('build/js/app.js') }}"></script>

    <script>
        $(document).ready(function() {
            var editEvent = new bootstrap.Modal(document.getElementById('event-modal'), {
                keyboard: false
            });

            var snowEditorData = {};

            snowEditorData.theme = 'snow',
                snowEditorData.modules = {
                    'toolbar': [
                        [{
                            'font': []
                        }, {
                            'size': []
                        }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{
                            'color': []
                        }, {
                            'background': []
                        }],
                        [{
                            'script': 'super'
                        }, {
                            'script': 'sub'
                        }],
                        [{
                            'header': [false, 1, 2, 3, 4, 5, 6]
                        }, 'blockquote', 'code-block'],
                        [{
                            'list': 'ordered'
                        }, {
                            'list': 'bullet'
                        }, {
                            'indent': '-1'
                        }, {
                            'indent': '+1'
                        }],
                        ['direction', {
                            'align': []
                        }],
                        ['link', 'image', 'video'],
                        ['clean']
                    ]
                }


            var quill = new Quill('#edit_event_description', snowEditorData);

            quill.on('text-change', (delta, oldDelta, source) => {
            });

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
                                success: function (response) {
                                    showSuccessMessage(response.message);
                                    $('#events_datatable').DataTable().ajax.reload(null, false); // false to keep the current page
                                },
                                error: function(xhr, status, error) {
                                    showErrorMessage(xhr.statusText);
                                }
                            });
                        }
                    });
                });

                $('.edit-btn').click(function () {
                    editEvent.show();
                    var id = $(this).attr('id');

                    $.ajax({
                        url: `/events/show/${id}`,
                        method: "GET",
                        success: function(response) {
                            displayEventValues(response.event);

                            var editorContent = quill.root;
                            editorContent.innerHTML = document.getElementById('event-description-field').value;
                        }
                    })
                })

                function displayEventValues(event) {
                    document.getElementById("event-id-field").value = event.id;
                    document.getElementById("title-field").value = event.title;
                    document.getElementById("event-type-field").value = event.type;
                    document.getElementById("event-section-field").value = event.section_id;
                    document.getElementById("event-reg-fee").value = event.reg_fee;
                    document.getElementById("event-location-field").value = event.location;
                    document.getElementById("event-latitude-field").value = event.latitude;
                    document.getElementById("event-longitude-field").value = event.longitude;
                    document.getElementById("event-description-field").value = event.description;

                    if(event.is_open_for_non_community) {
                        document.getElementById("is-open-for-non-community-checkbox").checked = true;
                    }

                    if(event.is_enable_event_registration) {
                        document.getElementById("is-enable-event-registration-checkbox").checked = true;
                    }

                    var st_date = event.start_date;
                    var ed_date = event.end_date;

                    var date_r = function formatDate(date) {
                        var d = new Date(date),
                            month = '' + (d.getMonth() + 1),
                            day = '' + d.getDate(),
                            year = d.getFullYear();
                        if (month.length < 2)
                            month = '0' + month;
                        if (day.length < 2)
                            day = '0' + day;
                        return [year, month, day].join('-');
                    };

                    var updateDay = null;

                    if(ed_date != null){
                        var endUpdateDay = new Date(ed_date);
                        updateDay = endUpdateDay.setDate(endUpdateDay.getDate());
                    }
                    
                    var er_date = ed_date == null ? (date_r(st_date)) : (date_r(st_date)) + ' to ' + (date_r(updateDay));
                
                    flatpickr(document.getElementById('event-date-field'), {
                        defaultDate: er_date,
                        altInput: true,
                        altFormat: "j F Y",
                        dateFormat: "Y-m-d",
                        mode: ed_date !== null ? "range" : "range",
                        onChange: function (selectedDates, dateStr, instance) {
                            var date_range = dateStr;
                        },
                    });

                    flatpickr("#event-time-field", {
                        defaultDate: event.time, // Set default value
                        noCalendar: true,
                        enableTime: true,
                        dateFormat: "H:i", 
                        time_24hr: false 
                    });
                }

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
                        render: function(data) {
                            if (data == null) {
                                return '<span class="text-capitalize">N/A</span>';
                            }

                            switch (data) {
                                case 'kids':
                                    return '<span class="text-capitalize badge bg-danger" style="font-size: 12px;">' +
                                        data + '</span>';
                                    break;
                                case 'youth':
                                    return '<span class="text-capitalize badge bg-primary" style="font-size: 12px;">' +
                                        data + '</span>';
                                    break;
                                case 'singles':
                                    return '<span class="text-capitalize badge bg-success" style="font-size: 12px;">' +
                                        data + '</span>';
                                    break;
                                case 'handmaids':
                                    return '<span class="text-capitalize badge bg-red" style="font-size: 12px;">' +
                                        data + '</span>';
                                    break;
                                case 'servants':
                                    return '<span class="text-capitalize badge bg-warning" style="font-size: 12px;">' +
                                        data + '</span>';
                                    break;
                                case 'couples':
                                    return '<span class="text-capitalize badge bg-info" style="font-size: 12px;">' +
                                        data + '</span>';
                                    break;
                            }

                            return '<span class="text-capitalize">' + data + '</span>';
                        }
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

                $('#addEventModal').on('hidden.bs.modal', function() {
                    if ($(this).attr('data-id') == 1) {
                        table.destroy();

                        initializeTables();

                        $(this).attr('data-id', 0);
                    }
                });
            }

            initializeTables();
        })
    </script>
@endsection
