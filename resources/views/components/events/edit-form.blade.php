@props(['event', 'sections' => []])
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

    #event-section-field+.select2-container {
        width: 100% !important;
    }

    #event-section-field+.select2-container .select2-selection--multiple {
        min-height: 38px;
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
        padding: 0.25rem 0.5rem;
    }

    #event-section-field+.select2-container.select2-container--focus .select2-selection--multiple {
        border-color: #86b7fe;
    }

    #event-section-field+.select2-container .select2-selection--multiple .select2-selection__rendered {
        display: flex;
        flex-wrap: wrap;
        gap: 0.375rem;
        padding: 0;
    }

    #event-section-field+.select2-container .select2-selection--multiple .select2-selection__choice {
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

    #event-section-field+.select2-container .select2-selection--multiple .select2-selection__choice__remove {
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

    #event-section-field+.select2-container .select2-selection--multiple .select2-selection__choice__display {
        display: inline-flex;
        align-items: center;
        min-width: 0;
        padding-left: 0 !important;
    }

    #event-section-field+.select2-container .select2-selection--multiple .select2-selection__choice__remove:hover {
        background: transparent;
        color: #fff;
    }

    #event-section-field+.select2-container .select2-results__option .select2-mfc-section {
        padding: 0.25rem 0;
    }
</style>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <form class="event-form" id="edit-event-form" enctype="multipart/form-data">
            @csrf
            <input type="hidden" id="event-id-field" value="{{ $event->id }}">
            <div class="px-1 pt-1 mb-3">
                <div class="position-relative mb-0 rounded-top overflow-hidden">
                    <img src="{{ URL::asset('build/images/small/img-8.jpg') }}" alt="Event cover"
                        class="img-fluid w-100" style="max-height: 220px; object-fit: cover;">
                    <div class="d-flex position-absolute start-0 end-0 top-0 p-4">
                        <div class="flex-grow-1">
                            <h4 class="text-white mb-1">Edit Event</h4>
                            <p class="text-white mb-0 opacity-75">Update event details, replace the poster, and keep
                                section branding consistent with the create page.</p>
                        </div>
                        <div class="flex-shrink-0">
                            <a href="{{ route('events.index') }}" class="btn btn-light">Back to List</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-4 pt-2">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="title-field" class="form-label">Title</label>
                            <input type="text" class="form-control" name="title" id="title-field"
                                value="{{ $event->title }}">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="event-type-field" class="form-label">Event Type</label>
                            <select name="type" id="event-type-field" class="form-select">
                                <option value="">Select Event Type</option>
                                @foreach ([1 => 'Worldwide', 2 => 'National', 3 => 'Regional', 4 => 'NCR', 5 => 'Area', 6 => 'Chapter', 7 => 'Unit', 8 => 'Household'] as $value => $label)
                                    <option value="{{ $value }}" @selected((string) $event->type === (string) $value)>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="event-section-field" class="form-label">Event Section</label>
                            <select name="section_ids[]" id="event-section-field" class="form-select" multiple>
                                @foreach ($sections as $section)
                                    <option value="{{ $section->id }}"
                                        data-section-slug="{{ strtolower($section->name) }}"
                                        @selected(in_array((string) $section->id, array_map('strval', (array) $event->section_ids), true))>
                                        {{ ucfirst($section->name) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="event-reg-fee" class="form-label">Registration Fee</label>
                            <div class="form-icon">
                                <input type="text" oninput="validateDigit(this)" id="event-reg-fee"
                                    class="form-control form-control-icon" name="reg_fee"
                                    placeholder="Leave blank if free..." value="{{ $event->reg_fee }}">
                                <i class="fst-normal">₱</i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 border border-gray-800 rounded p-2 mb-3">
                        <div class="">
                            <label class="form-label d-block">Early Bird Discount</label>
                            <div class="form-check form-switch mb-2">
                                <input type="checkbox" class="form-check-input" name="is_early_bird_enabled"
                                    id="is-early-bird-enabled-checkbox" value="1" @checked($event->is_early_bird_enabled)>
                                <label for="is-early-bird-enabled-checkbox" class="form-check-label">Enable Early
                                    Bird
                                    for paid registrations</label>
                            </div>
                            <div class="form-icon">
                                <input type="text" oninput="validateDigit(this)" id="early-bird-discount-field"
                                    class="form-control form-control-icon" name="early_bird_discount" placeholder="0.00"
                                    value="{{ $event->early_bird_discount }}" @disabled(!$event->is_early_bird_enabled || (float) $event->reg_fee <= 0)>
                                <i class="fst-normal">₱</i>
                            </div>
                            <small class="text-muted mt-2">Only the primary attendee gets this discount per
                                booking.</small>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label class="form-label" for="event-date-field">Event Date <span
                                    class="text-danger">*</span></label>
                            <div class="form-icon right">
                                <input class="form-control event-date-field" type="text" name="event_date"
                                    id="event-date-field" placeholder="Select Date..."
                                    value="{{ $event->start_date }}{{ $event->end_date ? ' to ' . $event->end_date : '' }}">
                                <i class='bx bx-calendar'></i>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="event-time-field" class="form-label">Event Time</label>
                            <div class="form-icon right">
                                <input type="text" name="time" id="event-time-field"
                                    class="form-control event-time-field" placeholder="Select Time..."
                                    data-provider="timepickr" data-time-basic="true" value="{{ $event->time }}">
                                <i class="ri-time-line"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-3">
                            <label for="event-location-field" class="form-label">Location <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="location"
                                placeholder="Event Location..." required id="event-location-field"
                                value="{{ $event->location }}">
                            <input type="hidden" name="latitude" id="event-latitude-field"
                                value="{{ $event->latitude }}">
                            <input type="hidden" name="longitude" id="event-longitude-field"
                                value="{{ $event->longitude }}">
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="mb-3">
                            <label for="description-field" class="form-label">Description</label>
                            <textarea name="description" id="event-description-field" cols="30" rows="5" class="form-control"
                                hidden>{{ $event->description }}</textarea>
                            <div id="edit_event_description" style="height: 220px;"></div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="mb-3">
                            <label for="event-poster-field" class="form-label">Poster</label>
                            @if ($event->poster)
                                <div class="mb-3">
                                    <img src="{{ URL::asset('uploads/events/' . $event->poster) }}"
                                        alt="{{ $event->title }} poster" class="img-fluid rounded border"
                                        style="max-height: 220px; object-fit: cover;">
                                </div>
                            @endif
                            <input type="file" class="filepond filepond-input-multiple" id="event-poster-field"
                                name="poster" data-max-file-size="3MB">
                            <small class="text-muted">Leave this empty if you want to keep the current poster.</small>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="d-flex flex-column flex-md-row gap-3 mt-2">
                            <div class="form-check form-radio-primary">
                                <input type="checkbox" class="form-check-input" name="is_open_for_non_community"
                                    id="is-open-for-non-community-checkbox" value="1"
                                    @checked($event->is_open_for_non_community)>
                                <label for="is-open-for-non-community-checkbox" class="form-check-label">Open for
                                    Non-Community</label>
                            </div>
                            <div class="form-check form-radio-secondary">
                                <input type="checkbox" class="form-check-input" name="is_enable_event_registration"
                                    id="is-enable-event-registration-checkbox" value="1"
                                    @checked($event->is_enable_event_registration)>
                                <label for="is-enable-event-registration-checkbox" class="form-check-label">Enable
                                    Event Registration</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 mt-3 @if (!$event->is_open_for_non_community) d-none @endif"
                        id="public-link-container" data-url-template="{{ route('events.show', '__EVENT_ID__') }}">
                        <a href="{{ $event->is_open_for_non_community ? route('events.show', $event->id) : '#' }}"
                            target="_blank" id="public-link-anchor" class="btn btn-primary">
                            Public Event Link
                        </a>
                    </div>
                </div>
                <hr>
                <div class="d-flex justify-content-end align-items-center gap-2">
                    <a href="{{ route('events.index') }}" class="btn btn-light">Cancel</a>
                    <button type="submit" class="btn btn-primary" id="update-event-button">Update Event</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="{{ URL::asset('build/libs/filepond/filepond.min.js') }}"></script>
<script src="{{ URL::asset('build/libs/filepond-plugin-image-preview/filepond-plugin-image-preview.min.js') }}">
</script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    let editEventPond;

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

    $("#event-section-field").select2({
        templateResult: formatMfcSectionOption,
        templateSelection: formatMfcSectionOption,
        escapeMarkup: function(markup) {
            return markup;
        }
    });

    function updatePublicLink(eventId, isOpenForNonCommunity) {
        const publicLinkContainer = document.getElementById("public-link-container");
        const publicLinkAnchor = document.getElementById("public-link-anchor");
        const urlTemplate = publicLinkContainer.dataset.urlTemplate;

        if (isOpenForNonCommunity && eventId) {
            const publicLink = urlTemplate.replace('__EVENT_ID__', eventId);
            publicLinkAnchor.href = publicLink;
            publicLinkAnchor.textContent = publicLink;
            publicLinkContainer.classList.remove('d-none');
            return;
        }

        publicLinkAnchor.href = '#';
        publicLinkAnchor.textContent = '#';
        publicLinkContainer.classList.add('d-none');
    }

    $("#is-open-for-non-community-checkbox").on('change', function() {
        updatePublicLink($("#event-id-field").val(), this.checked);
    });

    function syncEditEarlyBirdField() {
        const enableField = document.getElementById('is-early-bird-enabled-checkbox');
        const discountField = document.getElementById('early-bird-discount-field');
        const registrationFee = parseFloat(document.getElementById('event-reg-fee').value || '0') || 0;
        const shouldEnableDiscount = enableField.checked && registrationFee > 0;

        discountField.disabled = !shouldEnableDiscount;

        if (!shouldEnableDiscount) {
            discountField.value = '';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const snowEditorData = {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{
                        font: []
                    }, {
                        size: []
                    }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{
                        header: [false, 1, 2, 3, 4, 5, 6]
                    }],
                    ['direction', {
                        align: []
                    }],
                    ['link', 'image', 'video'],
                ]
            }
        };

        const quill = new Quill('#edit_event_description', snowEditorData);
        quill.root.innerHTML = document.getElementById('event-description-field').value || '';

        flatpickr("#event-date-field", {
            defaultDate: "{{ $event->start_date }}{{ $event->end_date ? ' to ' . $event->end_date : '' }}",
            altInput: true,
            altFormat: "F j, Y",
            dateFormat: "Y-m-d",
            mode: "range",
        });

        flatpickr("#event-time-field", {
            defaultDate: "{{ $event->time }}",
            noCalendar: true,
            enableTime: true,
            minuteIncrements: 5,
            altInput: true,
            altFormat: "h:i K",
            dateFormat: "H:i",
        });

        editEventPond = FilePond.create(document.querySelector('#event-poster-field'), {
            acceptedFileTypes: ['image/*'],
            allowMultiple: false,
        });

        syncEditEarlyBirdField();
        document.getElementById('event-reg-fee').addEventListener('input', syncEditEarlyBirdField);
        document.getElementById('is-early-bird-enabled-checkbox').addEventListener('change',
            syncEditEarlyBirdField);

        initializeEditLocationSearch();

        $("#edit-event-form").submit(function(e) {
            e.preventDefault();
            document.getElementById("event-description-field").value = quill.root.innerHTML;

            const submitButton = document.getElementById('update-event-button');
            const eventId = $('#event-id-field').val();
            const formData = new FormData(this);
            const files = editEventPond.getFiles();

            if (files.length > 0) {
                formData.set('poster', files[0].file);
            }

            formData.append('_method', 'PUT');
            formData.append('_token', document.querySelector('#edit-event-form input[name="_token"]')
                .value);

            submitButton.disabled = true;
            submitButton.textContent = 'Saving...';
            removeFieldsBorder();

            $.ajax({
                url: `/dashboard/events/${eventId}`,
                method: 'POST',
                data: formData,
                cache: false,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.status) {
                        toastr.success(response.message);
                        window.location.reload();
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422 && xhr.responseJSON?.errors) {
                        handleFieldsError(xhr.responseJSON.errors);
                    }

                    toastr.error(xhr.responseJSON?.message ?? "Error processing form.");
                    submitButton.disabled = false;
                    submitButton.textContent = 'Update Event';
                }
            });
        });
    });

    function removeFieldsBorder() {
        $('#edit-event-form input').removeClass('border-danger');
        $('#edit-event-form select').removeClass('border-danger');
        $('#edit-event-form .ql-container').removeClass('border border-danger');
        $('#edit-event-form .filepond--drop-label').removeClass('border-dashed border-danger rounded border-2');
    }

    function handleFieldsError(errors) {
        for (const property in errors) {
            $(`#edit-event-form input[name="${property}"]`).addClass('border-danger');
            $(`#edit-event-form select[name="${property}"]`).addClass('border-danger');

            if (property == "event_date") $('#edit-event-form input[name="event_date"]').next('input').addClass(
                'border-danger');
            if (property == "time") $('#edit-event-form input[name="time"]').next('input').addClass('border-danger');
            if (property == "description") $('#edit-event-form .ql-container').addClass('border border-danger');
            if (property == "poster") {
                $('#edit-event-form .filepond--drop-label').addClass('border-dashed border-danger rounded border-2');
            }
        }
    }

    function initializeEditLocationSearch() {
        const eventLocationInput = document.getElementById('event-location-field');
        const latitudeInput = document.getElementById('event-latitude-field');
        const longitudeInput = document.getElementById('event-longitude-field');

        if (!eventLocationInput || typeof google === 'undefined' || !google.maps?.places) {
            return;
        }

        const searchBox = new google.maps.places.SearchBox(eventLocationInput);
        searchBox.addListener('places_changed', () => {
            const place = searchBox.getPlaces()[0];
            if (place) {
                latitudeInput.value = place.geometry.location.lat();
                longitudeInput.value = place.geometry.location.lng();
            }
        });

        eventLocationInput.addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
            }
        });
    }
</script>
<script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDF42hfO7Dj8XFLrJY_SSF1bBM2Dj5XLQQ&libraries=places&callback=initializeEditLocationSearch"
    async></script>
