@props([
    'color' => '',
    'route' => '',
    'sections' => '',
])
<div class="modal fade" id="event-modal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header p-3 bg-info-subtle">
                <h5 class="modal-title" id="modal-title">Edit Event</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body p-4">
                <form class="event-form" id="edit-event-form">
                    @csrf
                    <input type="hidden" id="event-id-field">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="title-field" class="form-label">Title</label>
                                <input type="text" class="form-control" name="title" id="title-field">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="event-type-field" class="form-label">Event Type</label>
                                <select name="type" id="event-type-field" class="form-select">
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
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="event-section-field" class="form-label">Event Type</label>
                                <select name="section_ids[]" id="event-section-field" class="form-select" multiple>
                                    <option value="">Select Event Section</option>
                                    @foreach ($sections as $section)
                                      <option value="{{ $section->id }}">{{ $section->name}}</option>  
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
                                        placeholder="Leave blank if free...">
                                    <i class="fst-normal">₱</i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label" for="event-date-field">Event Date <span
                                        class="text-danger">*</span></label>
                                <div class="form-icon right">
                                    <input class="form-control event-date-field" type="text" name="event_date"
                                        id="event-date-field" data-provider="flatpickr" data-date-format="Y-m-d"
                                        data-range-date="true" placeholder="Select Date...">
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
                                        data-provider="timepickr" data-time-basic="true">
                                    <i class="ri-time-line"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="event-location-field" class="form-label">Location <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="location"
                                    placeholder="Event Location..." required id="event-location-field">
                                <input type="hidden" name="latitude" id="event-latitude-field" value="">
                                <input type="hidden" name="longitude" id="event-longitude-field" value="">
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label for="description-field" class="form-label">Description</label>
                                <textarea name="description" id="event-description-field" cols="30" rows="5" class="form-control" hidden></textarea>
                                <div class="" id="edit_event_description" style="height: 200px;"></div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="d-flex gap-5 mt-2">
                                <div class="form-check form-radio-primary">
                                    <input type="checkbox" class="form-check-input"
                                        name="is_open_for_non_community" id="is-open-for-non-community-checkbox"
                                        value="1">
                                    <label for="is-open-for-non-community-checkbox" class="form-check-label">Open for
                                        Non-Community</label>
                                </div>
                                <div class="form-check form-radio-secondary">
                                    <input type="checkbox" class="form-check-input"
                                        name="is_enable_event_registration" id="is-enable-event-registration-checkbox"
                                        value="1">
                                    <label for="is-enable-event-registration-checkbox" class="form-check-label">Enable
                                        Event
                                        Registration</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-end align-items-center">
                        <button type="submit" class="btn btn-primary">Update Event</button>
                    </div>
                </form>
            </div>
        </div> <!-- end modal-content-->
    </div> <!-- end modal dialog-->
</div> <!-- end modal-->

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $("#event-section-field").select2();

    $("#edit-event-form").submit(function (e) {
        e.preventDefault();
        let description_content = $('#edit_event_description .ql-editor').html();
        document.getElementById("event-description-field").value = description_content;

        let event_id = $('#event-id-field').val();
        let formData = new FormData(this);

        formData.append('_method', 'PUT'); // Laravel needs this to treat it as a PUT request
        formData.append('_token', document.querySelector('input[name="_token"]').value);

        $.ajax({
            url: `/dashboard/events/${event_id}`,
            method: 'POST',
            data: formData,
            cache: false,
            processData: false,
            contentType: false,
            success: function(response) {
                if(response.status) {
                    toastr.success(response.message);
                    location.reload();
                }
            },
            error: function(xhr, status, error) {
                toastr.failed("Error processing form.");
            }
        });
    })
</script>