@props([
    'color' => '',
    'route' => '',
])
<div class="modal fade" id="addEventModal" data-id="0" tabindex="-1" aria-hidden="true">
    <style>
        .pac-container { z-index: 100000 !important; }
    </style>
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" data-simplebar>
        <div class="modal-content border-0">
            <div class="modal-body">
                <form autocomplete="off" id="event-form" class="needs-validation" novalidate
                    action="{{ $route }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-lg-12">
                            {{-- <input type="hidden" id="memberid-input" class="form-control" value=""> --}}
                            <div class="px-1 pt-1 mb-3">
                                <div
                                    class="modal-team-cover position-relative mb-0 mt-n4 mx-n4 rounded-top overflow-hidden">
                                    <img src="{{ URL::asset('build/images/small/img-8.jpg') }}" alt=""
                                        id="cover-img" class="img-fluid">

                                    <div class="d-flex position-absolute start-0 end-0 top-0 p-3">
                                        <div class="flex-grow-1">
                                            <h5 class="modal-title text-white" id="createMemberLabel">Add
                                                New Event</h5>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <div class="d-flex gap-3 align-items-center">
                                                <button type="button" class="btn-close btn-close-white"
                                                    id="createMemberBtn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @component('components.input_fields.basic')
                                @slot('id')
                                    event_title
                                @endslot
                                @slot('name')
                                    title
                                @endslot
                                @slot('label')
                                    Title
                                @endslot
                                @slot('placeholder')
                                    Event Title
                                @endslot
                                @slot('feedback')
                                    Invalid input. Minimum of 3 characters!
                                @endslot
                            @endcomponent

                            <div class="row">
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label for="event_type" class="form-label">Event Type</label>
                                        <select name="type" id="event_type" class="form-select">
                                            <option value="">Select Event Type</option>
                                            <option value="1">Worldwide</option>
                                            <option value="2">National</option>
                                            <option value="3">Regional</option>
                                            <option value="4">NCR</option>
                                            <option value="5">Area</option>
                                            <option value="6">Chapter</option>
                                            <option value="7">Unit</option>
                                            <option value="8">Household</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="mb-3">
                                        <label for="event_section" class="form-label">Event Type</label>
                                        <select name="section_id" id="event_section" class="form-select">
                                            <option value="">Select Event Section</option>
                                            <option value="1">Kids</option>
                                            <option value="2">Youth</option>
                                            <option value="3">Singles</option>
                                            <option value="4">Handmaids</option>
                                            <option value="5">Servants</option>
                                            <option value="6">Couples</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="event_date">Event Date <span
                                                class="text-danger">*</span></label>
                                        <div class="form-icon right">
                                            <input class="form-control event_date" type="text" name="event_date"
                                                id="event_date" data-provider="flatpickr" data-date-format="Y-m-d"
                                                data-range-date="true" placeholder="Select Date...">
                                            <i class='bx bx-calendar'></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="mb-3">
                                        <label for="event_time" class="form-label">Event Time</label>
                                        <div class="form-icon right">
                                            <input type="text" name="time" id="event_time"
                                                class="form-control event_time" placeholder="Select Time..."
                                                data-provider="timepickr" data-time-basic="true">
                                            <i class="ri-time-line"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="event_location" class="form-label">Location <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="location"
                                        placeholder="Event Location..." required id="event_location">
                                    <input type="hidden" name="latitude" id="latitude" value="">
                                    <input type="hidden" name="longitude" id="longitude" value="">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label for="event_reg_fee" class="form-label">Registration Fee</label>
                                        <div class="form-icon">
                                            <input type="text" oninput="validateDigit(this)" id="event_reg_fee"
                                                class="form-control form-control-icon" name="reg_fee"
                                                placeholder="Leave blank if free...">
                                            <i class="fst-normal">₱</i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label for="" class="form-label">Category <span
                                            class="text-danger">*</span></label>
                                    <div class="d-flex gap-5 mt-2">
                                        <div class="form-check form-radio-primary">
                                            <input type="checkbox" class="form-check-input"
                                                name="is_open_for_non_community" id="is_open_for_non_community"
                                                value="1" checked>
                                            <label for="is_open_for_non_community" class="form-check-label">Open for
                                                Non-Community</label>
                                        </div>
                                        <div class="form-check form-radio-secondary">
                                            <input type="checkbox" class="form-check-input"
                                                name="is_enable_event_registration" id="is_enable_event_registration"
                                                value="1">
                                            <label for="is_enable_event_registration" class="form-check-label">Enable
                                                Event
                                                Registration</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label for="event_poster" class="form-label">Poster <span
                                            class="text-danger">*</span></label>
                                    <input type="file" class="filepond filepond-input-multiple" id="event_poster"
                                        name="poster" data-max-file-size="3MB" required>
                                    <input type="hidden" id="image_input" name="image_input">
                                </div>
                            </div> <!-- end col -->

                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="event_description" class="form-label">Description <span
                                            class="text-danger">*</span></label>
                                    <textarea name="description" id="event_description_input" hidden></textarea>
                                    <div class="" id="event_description" style="height: 300px;"></div>
                                    <!-- end Snow-editor-->
                                </div>
                            </div>

                            <div class="hstack gap-2 justify-content-end">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary" id="addNewEvent">Create
                                    Event</button>
                            </div>

                            <input type="text" hidden id="success_or_fail" value="0">
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!--end modal-content-->
    </div>
    <!--end modal-dialog-->
</div>
<!-- Warning Toast -->
<div style="z-index: 9999999; position: absolute; top: 20px; right: 20px;">
    <div id="warningToast" class="toast toast-border-warning overflow-hidden mt-3" role="alert"
        aria-live="assertive" aria-atomic="true">
        <div class="toast-body">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0 me-2">
                    <i class="ri-notification-off-line align-middle"></i>
                </div>
                <div class="flex-grow-1">
                    <h6 class="mb-0">Please fill out the required fields.</h6>
                </div>
                <div class="flex-shrink-0 me-2 close" style="cursor: pointer;">
                    <i class="ri-close-fill align-middle"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Success Toast -->
<div style="z-index: 9999999; position: absolute; top: 20px; right: 20px;">
    <div id="successToast" class="toast toast-border-success overflow-hidden mt-3" role="alert"
        aria-live="assertive" aria-atomic="true">
        <div class="toast-body">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0 me-2">
                    <i class="ri-checkbox-circle-fill align-middle"></i>
                </div>
                <div class="flex-grow-1">
                    <h6 class="mb-0">Yey! Registration Successful.</h6>
                </div>
                <div class="flex-shrink-0 me-2 close" style="cursor: pointer;">
                    <i class="ri-close-fill align-middle"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDEmTK1XpJ2VJuylKczq2-49A6_WuUlfe4&libraries=places&callback=initialize" async defer></script>
<script>
    function initialize() {
        const eventLocationInput = document.getElementById('event_location');
        const latitudeInput = document.getElementById('latitude');
        const longitudeInput = document.getElementById('longitude');

        if (!eventLocationInput) {
            console.error('Event location input not found.');
            return;
        }

        // Initialize Google Places SearchBox
        const searchBox = new google.maps.places.SearchBox(eventLocationInput);

        searchBox.addListener('places_changed', () => {
            console.log("test");
            const places = searchBox.getPlaces();
            if (places.length === 0) {
                return;
            }

            const place = places[0];
            const lat = place.geometry.location.lat();
            const lng = place.geometry.location.lng();

            // Set latitude and longitude values
            latitudeInput.value = lat;
            longitudeInput.value = lng;
        });
    }


    // Prevent form submission on Enter key in event location input
    document.getElementById('event_location').addEventListener('keydown', function(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
        }
    });

    $('#addEventModal').on('shown.bs.modal', function () {
        initialize();
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var warningToastEl = document.getElementById('warningToast');
        var warningToast = new bootstrap.Toast(warningToastEl);

        $('.close').on('click', function() {
            warningToast.hide();
        });

        var successToastEl = document.getElementById('successToast');
        var successToast = new bootstrap.Toast(successToastEl);

        $('.close').on('click', function() {
            successToast.hide();
        });

        let tomorrow = moment().add(1, 'days').format('YYYY-MM-DD');

        var dateInput = document.getElementById('event_date');
        var timeInput = document.getElementById('event_time');

        flatpickr(dateInput, {
            mode: "range",
            minDate: tomorrow,
            altInput: true,
            altFormat: "F j, Y",
            dateFormat: 'Y-m-d',
        })


        flatpickr(timeInput, {
            noCalendar: true,
            enableTime: true,
            minuteIncrements: 5,
            altInput: true,
            altFormat: "h:i K",
            dateFormat: "h:i",
        })

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


        var quill = new Quill('#event_description', snowEditorData);

        // Configure FilePond
        const inputElement = document.querySelector('#event_poster');

        const pond = FilePond.create(inputElement, {
            acceptedFileTypes: ['image/*'],
            allowMultiple: false,
        });

        var form = document.getElementById('event-form');

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            var editorContent = quill.root.innerHTML;
            var desc = document.getElementById('event_description_input');
            desc.value = editorContent;

            if (!dateInput.value) {
                $('input.form-control.event_date.form-control.input').removeClass('border-success');
                $('input.form-control.event_date.form-control.input').addClass('border-danger');
            } else {
                $('input.form-control.event_date.form-control.input').removeClass('border-danger');
                $('input.form-control.event_date.form-control.input').addClass('border-success');
            }

            $('input.form-control.event_time.form-control.input').addClass('border-success');


            const files = pond.getFiles();
            if (files.length === 0) {
                $('.filepond--drop-label').addClass('border-dashed border-danger rounded border-2');
            }


            if (editorContent == '<p><br></p>') {
                $('.ql-snow.ql-container').removeClass('border-success', 'border-top-0');
                $('.ql-snow.ql-toolbar').removeClass('border-success');

                $('.ql-snow.ql-container').addClass('border-danger', 'border-top-0');
                $('.ql-snow.ql-toolbar').addClass('border-danger');
            } else {
                $('.ql-snow.ql-container').removeClass('border-danger', 'border-top-0');
                $('.ql-snow.ql-toolbar').removeClass('border-danger');

                $('.ql-snow.ql-container').addClass('border-success', 'border-top-0');
                $('.ql-snow.ql-toolbar').addClass('border-success');
            }

            // const editorContent = document.querySelector('.ql-editor').innerHTML;

            // Create a FormData object
            const formData = new FormData(this);

            if (files.length > 0) {
                formData.append('poster', files[0].file);
            }

            $.ajax({
                url: "{{ route('events.store') }}",
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                processData: false,
                contentType: false,
                data: formData,
                success: function(data) {
                    $('#addEventModal').modal('hide');

                    $('#addEventModal').attr('data-id', 1);
                    $('#event-form')[0].reset();

                    if ($('#event-form')[0].reset()) {
                        console.log('reset');
                    }
                    successToast.show();

                    location.reload();
                    // Handle the response as needed
                },
                error: function(xhr, textStatus, errorThrown, response) {
                    if (xhr.status === 422) {
                        // Handle validation errors
                        const errors = xhr.responseJSON.errors;

                        warningToast.show();

                        if (errors.event_date[0]) {
                            console.log(errors.event_date[0]);

                            $('input.form-control.event_date.input').addClass(
                                'border-danger');
                        } else {
                            $('input.form-control.event_date.input').removeClass(
                                'border-danger');
                            $('input.form-control.event_date.input').addClass(
                                'border-success');
                        }
                    }
                    console.error('Error:', textStatus, errorThrown);
                }
            });

            // this.submit();
        });

    });
</script>
