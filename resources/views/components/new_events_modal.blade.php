@props([
    'color' => '',
    'route' => '',
])
<div class="modal fade" id="addEventModal" data-id="0" tabindex="-1" aria-hidden="true">
    <style>
        .pac-container {
            z-index: 100000 !important;
        }

        .select2-mfc-section {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            min-width: 0;
        }

        .select2-mfc-section__logo {
            width: 1.5rem;
            height: 1.5rem;
            object-fit: contain;
            flex-shrink: 0;
        }

        #addEventModal .select2-container {
            width: 100% !important;
        }

        #addEventModal .select2-container--default .select2-selection--multiple {
            min-height: 38px;
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
            padding: 0.25rem 0.5rem;
        }

        #addEventModal .select2-container--default.select2-container--focus .select2-selection--multiple {
            border-color: #86b7fe;
        }

        #addEventModal .select2-container--default .select2-selection--multiple .select2-selection__rendered {
            display: flex;
            flex-wrap: wrap;
            gap: 0.375rem;
            padding: 0;
        }

        #addEventModal .select2-container--default .select2-selection--multiple .select2-selection__choice {
            display: inline-flex;
            align-items: center;
            background-color: #405189;
            border: 0;
            border-radius: 0.375rem;
            color: #fff;
            margin: 0;
            padding: 0.375rem 0.625rem 0.375rem 1.5rem;
            position: relative;
            line-height: 1.2;
        }

        #addEventModal .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: #fff;
            border-right: 0;
            left: 0.375rem;
            margin-right: 0;
            padding: 0;
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: auto;
        }

        #addEventModal .select2-container--default .select2-selection--multiple .select2-selection__choice__display {
            display: inline-flex;
            align-items: center;
            min-width: 0;
        }

        #addEventModal .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
            background: transparent;
            color: #fff;
        }

        #addEventModal .select2-container--default .select2-results__option .select2-mfc-section {
            padding: 0.25rem 0;
        }

        #addEventModal .select2-container--default .select2-selection--multiple .select2-selection__choice__display {
            padding-left: 0px !important;
        }
    </style>
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" data-simplebar>
        <div class="modal-content border-0">
            <div class="modal-body">
                <form autocomplete="off" id="event-form" class="needs-validation" novalidate action="{{ $route }}"
                    method="POST" enctype="multipart/form-data">
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
                                        <label for="event_section" class="form-label">Event Section</label>
                                        <select name="section_ids[]" id="event_section" multiple="multiple"
                                            class="form-select">
                                            <option value="">Select Event Section</option>
                                            <option value="1" data-section-slug="kids">Kids</option>
                                            <option value="2" data-section-slug="youth">Youth</option>
                                            <option value="3" data-section-slug="singles">Singles</option>
                                            <option value="4" data-section-slug="handmaids">Handmaids</option>
                                            <option value="5" data-section-slug="servants">Servants</option>
                                            <option value="6" data-section-slug="couples">Couples</option>
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
                                            <input class="form-control" type="text" name="event_date" id="event_date"
                                                placeholder="Select Date...">
                                            <i class='bx bx-calendar'></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="mb-3">
                                        <label for="event_time" class="form-label">Event Time</label>
                                        <div class="form-icon right">
                                            <input type="text" name="time" id="event_time" class="form-control"
                                                placeholder="Select Time..." data-provider="timepickr"
                                                data-time-basic="true">
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
                                    <label for="" class="form-label"></label>
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
                                <button type="button" class="btn btn-primary" id="addNewEvent">Create
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

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    let pond;
    let eventDescriptionEditor;

    const mfcSectionAssets = {
        kids: "{{ URL::asset('build/images/kids-logo.png') }}",
        youth: "{{ URL::asset('build/images/youth-logo.png') }}",
        singles: "{{ URL::asset('build/images/singles-logo.png') }}",
        handmaids: "{{ URL::asset('build/images/handmaid-logo.png') }}",
        servants: "{{ URL::asset('build/images/servant-logo.png') }}",
        couples: "{{ URL::asset('build/images/couples-logo.png') }}",
    };

    function formatMfcSectionOption(option) {
        if (!option.id) {
            return option.text;
        }

        const sectionSlug = option.element?.dataset?.sectionSlug;
        const logo = sectionSlug ? mfcSectionAssets[sectionSlug] : null;

        if (!logo) {
            return option.text;
        }

        return $(`
            <span class="select2-mfc-section">
                <img src="${logo}" alt="${option.text} logo" class="select2-mfc-section__logo">
                <span>${option.text}</span>
            </span>
        `);
    }

    $("#event_section").select2({
        templateResult: formatMfcSectionOption,
        templateSelection: formatMfcSectionOption,
        escapeMarkup: function(markup) {
            return markup;
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        let dateInput = document.getElementById('event_date');
        let timeInput = document.getElementById('event_time');


        setUpFlatPicker(dateInput, timeInput);

        setUpQuillEditor();

        setUpFilePond();

        $('#event-form').on('submit', function(e) {
            e.preventDefault();
        })

        let add_new_event_btn = document.getElementById('addNewEvent');

        add_new_event_btn.addEventListener('click', submitCreatedEvent);

        $('#addEventModal').on('hidden.bs.modal', function() {
            syncEventDescriptionInput();

            if (eventDescriptionEditor) {
                eventDescriptionEditor.setContents([]);
            }

            document.getElementById('event_description_input').value = '';
        });
    });

    function submitCreatedEvent(e) {
        e.preventDefault();

        syncEventDescriptionInput();

        var form = document.getElementById('event-form');
        const formData = new FormData(form);

        const files = pond.getFiles();
        if (files.length > 0) {
            formData.append('poster', files[0].file);
        }

        e.target.innerHTML = "Saving...";
        e.target.setAttribute("disabled", true);

        removeFieldsBorder();

        $.ajax({
            url: "{{ route('events.store') }}",
            method: 'POST',
            headers: {
                'Accept': "application/json",
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            processData: false,
            contentType: false,
            data: formData,
            success: function(data) {
                $('#addEventModal').modal('hide');
                $('#addEventModal').attr('data-id', 1);

                e.target.innerHTML = "Create Event";
                e.target.removeAttribute("disabled");

                location.reload();
            },
            error: function(xhr, textStatus, errorThrown) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    handleFieldsError(errors);
                }
                toastr.error(xhr.responseJSON.message);

                e.target.innerHTML = "Create Event";
                e.target.removeAttribute("disabled");
            }
        });
    }

    const setUpFlatPicker = (dateInput, timeInput) => {
        let tomorrow = moment().add(1, 'days').format('YYYY-MM-DD');

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
    }

    const setUpQuillEditor = () => {
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
                        'header': [false, 1, 2, 3, 4, 5, 6]
                    }],
                    ['direction', {
                        'align': []
                    }],
                    ['link', 'image', 'video'],
                ]
            }


        eventDescriptionEditor = new Quill('#event_description', snowEditorData);
        eventDescriptionEditor.on('text-change', syncEventDescriptionInput);
    }

    const syncEventDescriptionInput = () => {
        const desc = document.getElementById('event_description_input');
        const editor = document.querySelector('#event_description .ql-editor');
        const editorHtml = editor ? editor.innerHTML : '';
        const editorText = editor ? editor.textContent.trim() : '';

        desc.value = editorText.length ? editorHtml : '';
    }

    const setUpFilePond = () => {
        const inputElement = document.querySelector('#event_poster');

        pond = FilePond.create(inputElement, {
            acceptedFileTypes: ['image/*'],
            allowMultiple: false,
        });
    }

    const removeFieldsBorder = () => {
        $('input').removeClass('border-danger');
        $('select').removeClass('border-danger');
        $('.filepond--drop-label').removeClass('border-danger');
    }

    const handleFieldsError = (errors) => {
        for (const property in errors) {
            $(`input[name="${property}"]`).addClass('border-danger');
            $(`select[name="${property}"]`).addClass('border-danger');

            if (property == "event_date") $('input[name="event_date"]').next('input').addClass('border-danger');
            if (property == "time") $('input[name="time"]').next('input').addClass('border-danger');

            if (property == "poster") {
                $('.filepond--drop-label').addClass('border-dashed border-danger rounded border-2');
            }
        }
    }
</script>
