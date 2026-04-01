@extends('layouts.master')
@section('title')
    @lang('translation.settings')
@endsection
@section('content')
    <div class="position-relative mx-n4 mt-n4">
        <div class="profile-wid-bg profile-setting-img">
            <img src="{{ URL::asset('build/images/profile-bg.jpg') }}" class="profile-wid-img" alt="">
            <div class="overlay-content">
                <div class="text-end p-3">
                    {{-- <div class="p-0 ms-auto rounded-circle profile-photo-edit">
                        <input id="profile-foreground-img-file-input" type="file"
                            class="profile-foreground-img-file-input">
                        <label for="profile-foreground-img-file-input" class="profile-photo-edit btn btn-light">
                            <i class="ri-image-edit-line align-bottom me-1"></i> Change Cover
                        </label>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xxl-3">
            <div class="card mt-n5">
                <div class="card-body p-4">
                    <div class="text-center">
                        <div class="profile-user position-relative d-inline-block mx-auto  mb-4">
                            <img src="@if (Auth::user()->avatar != '') {{ URL::asset('uploads/avatars/' . Auth::user()->avatar) }} @else{{ URL::asset('build/images/users/avatar-1.jpg') }} @endif"
                                class="  rounded-circle avatar-xl img-thumbnail user-profile-image"
                                alt="user-profile-image">
                            <div class="avatar-xs p-0 rounded-circle profile-photo-edit">
                                <input id="profile-img-file-input" type="file" class="profile-img-file-input">
                                <label for="profile-img-file-input" class="profile-photo-edit avatar-xs">
                                    <span class="avatar-title rounded-circle bg-light text-body">
                                        <i class="ri-camera-fill"></i>
                                    </span>
                                </label>
                            </div>
                        </div>
                        <h5 class="fs-16 mb-1">{{ Auth::user()->first_name ?? 'Sample' }}
                            {{ Auth::user()->last_name ?? 'User' }}</h5>
                        <p class="text-muted mb-0">MFC ID: <span id="mfc-id">{{ Auth::user()->mfc_id_number }}</span> <a
                                href="javascript:void(0);" class="ri-file-copy-line" id="copy-mfc-id"
                                data-bs-toggle="tooltip" data-bs-placement="right"></a></p>
                        <script>
                            document.getElementById('copy-mfc-id').addEventListener('click', function(event) {
                                event.preventDefault(); // Prevent default action

                                const mfcId = document.getElementById('mfc-id').textContent;
                                navigator.clipboard.writeText(mfcId).then(function() {
                                    alert('MFC ID copied to clipboard!');
                                }, function(err) {
                                    console.error('Could not copy text: ', err);
                                });
                            });
                        </script>
                    </div>
                </div>
            </div>
            <!--end card-->
        </div>
        <!--end col-->
        <div class="col-xxl-9">
            <div class="card mt-xxl-n5">
                <div class="card-header">
                    <ul class="nav nav-tabs-custom rounded card-header-tabs d-flex justify-content-between border-bottom-0"
                        role="tablist">
                        <div class="d-flex">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#personalDetails" role="tab">
                                    <i class="fas fa-home"></i>
                                    Personal Details
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#service" role="tab">
                                    <i class="far fa-envelope"></i>
                                    Service
                                </a>
                            </li>
                        </div>
                        <div>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#changePassword" role="tab">
                                    <i class="far fa-user"></i>
                                    Change Password
                                </a>
                            </li>
                        </div>
                    </ul>
                </div>
                <div class="card-body p-4">
                    <div class="tab-content">
                        <!-- personalDetails -->
                        <div class="tab-pane active" id="personalDetails" role="tabpanel">
                            <form action="{{ route('users.profile.update', $user->id) }}" method="POST" id="profileForm">
                                @csrf
                                @method('PUT')
                                <input type="hidden" id="user-id-field" value="{{ $user->id }}">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="firstnameInput" class="form-label">First
                                                Name</label>
                                            <input type="text" class="form-control" id="firstnameInput"
                                                placeholder="Enter your firstname" name="first_name"
                                                value="{{ $user->first_name }}">
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="lastnameInput" class="form-label">Last
                                                Name</label>
                                            <input type="text" class="form-control" id="lastnameInput"
                                                placeholder="Enter your lastname" name="last_name"
                                                value="{{ $user->last_name }}">
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label for="phonenumberInput" class="form-label">Phone
                                                Number</label>
                                            <input name="contact_number" type="text" class="form-control"
                                                id="phonenumberInput" placeholder="Enter your phone number"
                                                value="{{ $user->contact_number }}">
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label for="emailInput" class="form-label">Email
                                                Address</label>
                                            <input type="email" name="email" class="form-control" id="emailInput"
                                                placeholder="Enter your email" value="{{ $user->email }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label for="birthday-field" class="form-label">Birthday</label>
                                            <input type="text" class="form-control" id="birthday-field"
                                                name="birthday" value="{{ optional($user->user_details)->birthday }}"
                                                placeholder="Select date" />
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label for="JoiningdatInput" class="form-label">Joining
                                                Date</label>
                                            <input type="text" class="form-control" data-provider="flatpickr"
                                                data-altFormat="F j, Y" id="JoiningdatInput" name="created_at"
                                                value="{{ $user->created_at }}" placeholder="Select date" />
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-4">
                                        <x-input_fields.choices label="MFC Section" id="mfc_section" formId="profileForm"
                                            name="section_id" :isMfcSection="true">
                                            @foreach ($sections as $section)
                                                <option value="{{ $section->id }}"
                                                    data-custom-properties='@json(['sectionSlug' => $section->name])'
                                                    {{ $section->id == $user->section_id ? 'selected' : null }}>
                                                    {{ ucfirst($section->name) }}
                                                </option>
                                            @endforeach
                                        </x-input_fields.choices>
                                    </div>
                                    <!--end col-->

                                    <!--end col-->
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label for="mfc_area" class="form-label">Area</label>
                                            <select name="area" id="mfc_area" data-choices data-choices-sorting-false>
                                                <option {{ $user->area == '' ? 'selected' : null }} value="">Select
                                                    Area</option>
                                                <option {{ $user->area == 'ncr_north' ? 'selected' : null }}
                                                    value="ncr_north">NCR - North</option>
                                                <option {{ $user->area == 'ncr_south' ? 'selected' : null }}
                                                    value="ncr_south">NCR - South</option>
                                                <option {{ $user->area == 'ncr_east' ? 'seleected' : null }}
                                                    value="ncr_east">NCR - East</option>
                                                <option {{ $user->area == 'ncr_cental' ? 'selected' : null }}
                                                    value="ncr_cental">NCR - Central</option>
                                                <option {{ $user->area == 'south_luzon' ? 'selected' : null }}
                                                    value="south_luzon">South Luzon</option>
                                                <option {{ $user->area == 'north_and_central_luzon' ? 'selected' : null }}
                                                    value="north_and_central_luzon">North & Central Luzon</option>
                                                <option {{ $user->area == 'visayas' ? 'selected' : null }}
                                                    value="visayas">Visayas</option>
                                                <option {{ $user->area == 'mindanao' ? 'selected' : null }}
                                                    value="mindanao">Mindanao</option>
                                                <option {{ $user->area == 'international' ? 'selected' : null }}
                                                    value="international">International</option>
                                                <option {{ $user->area == 'baguio' ? 'selected' : null }} value="baguio">
                                                    Baguio</option>
                                                <option {{ $user->area == 'palawan' ? 'selected' : null }}
                                                    value="palawan">Palawan</option>
                                                <option {{ $user->area == 'batangas' ? 'selected' : null }}
                                                    value="batangas">Batangas</option>
                                                <option {{ $user->area == 'laguna' ? 'selected' : null }} value="laguna">
                                                    Laguna</option>
                                                <option {{ $user->area == 'pampanga' ? 'selected' : null }}
                                                    value="pampanga">Pampanga</option>
                                                <option {{ $user->area == 'tarlac' ? 'selected' : null }} value="tarlac">
                                                    Tarlac</option>
                                                <option {{ $user->area == 'other' ? 'selected' : null }} value="other">
                                                    Other</option>
                                            </select>
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label for="god-given-skill-select" class="form-label">God-given
                                                Skills</label>
                                            <select class="form-select" name="god_given_skills[]"
                                                id="god-given-skill-select" multiple="multiple">
                                                <option value="">Select a Skill</option>
                                                <optgroup label="Spiritual and Pastoral Skills">
                                                    <option
                                                        {{ in_array('Prayer Leading', optional($user->user_details)->god_given_skills ?? []) ? 'selected' : null }}
                                                        value="Prayer Leading">
                                                        Prayer Leading
                                                    </option>
                                                    <option
                                                        {{ in_array('Bible Study Facilitation', optional($user->user_details)->god_given_skills ?? []) ? 'selected' : null }}
                                                        value="Bible Study Facilitation">
                                                        Bible Study Facilitation
                                                    </option>
                                                    <option
                                                        {{ in_array('Spiritual Counseling', optional($user->user_details)->god_given_skills ?? []) ? 'selected' : null }}
                                                        value="Spiritual Counseling">
                                                        Spiritual Counseling
                                                    </option>
                                                    <option
                                                        {{ in_array('Worship Leading', optional($user->user_details)->god_given_skills ?? []) ? 'selected' : null }}
                                                        value="Worship Leading">
                                                        Worship Leading
                                                    </option>
                                                    <option
                                                        {{ in_array('Catechism Teaching', optional($user->user_details)->god_given_skills ?? []) ? 'selected' : null }}
                                                        value="Catechism Teaching">
                                                        Catechism Teaching
                                                    </option>
                                                </optgroup>

                                                <optgroup label="Community and Social Skills">
                                                    <option
                                                        {{ in_array('Event Planning and Coordination', optional($user->user_details)->god_given_skills ?? []) ? 'selected' : null }}
                                                        value="Event Planning and Coordination">
                                                        Event Planning and Coordination
                                                    </option>
                                                    <option
                                                        {{ in_array('Community Outreach', optional($user->user_details)->god_given_skills ?? []) ? 'selected' : null }}
                                                        value="Community Outreach">
                                                        Community Outreach
                                                    </option>
                                                    <option
                                                        {{ in_array('Fundraising and Development', optional($user->user_details)->god_given_skills ?? []) ? 'selected' : null }}
                                                        value="Fundraising and Development">
                                                        Fundraising and Development
                                                    </option>
                                                    <option
                                                        {{ in_array('Volunteer Management', optional($user->user_details)->god_given_skills ?? []) ? 'selected' : null }}
                                                        value="Volunteer Management">
                                                        Volunteer Management
                                                    </option>
                                                    <option
                                                        {{ in_array('Conflict Resolution', optional($user->user_details)->god_given_skills ?? []) ? 'selected' : null }}
                                                        value="Conflict Resolution">
                                                        Conflict Resolution
                                                    </option>
                                                </optgroup>

                                                <optgroup label="Administrative and Technical Skills">
                                                    <option
                                                        {{ in_array('Administration and Office Management', optional($user->user_details)->god_given_skills ?? []) ? 'selected' : null }}
                                                        value="Administration and Office Management">
                                                        Administration and Office Management
                                                    </option>
                                                    <option
                                                        {{ in_array('Financial Management and Accounting', optional($user->user_details)->god_given_skills ?? []) ? 'selected' : null }}
                                                        value="Financial Management and Accounting">
                                                        Financial Management and Accounting
                                                    </option>
                                                    <option
                                                        {{ in_array('IT Support and Systems Management', optional($user->user_details)->god_given_skills ?? []) ? 'selected' : null }}
                                                        value="IT Support and Systems Management">
                                                        IT Support and Systems Management
                                                    </option>
                                                    <option
                                                        {{ in_array('Website Development and Maintenance', optional($user->user_details)->god_given_skills ?? []) ? 'selected' : null }}
                                                        value="Website Development and Maintenance">
                                                        Website Development and Maintenance
                                                    </option>
                                                    <option
                                                        {{ in_array('Graphic Design', optional($user->user_details)->god_given_skills ?? []) ? 'selected' : null }}
                                                        value="Graphic Design">
                                                        Graphic Design
                                                    </option>
                                                    <option
                                                        {{ in_array('Content Creation and Management', optional($user->user_details)->god_given_skills ?? []) ? 'selected' : null }}
                                                        value="Content Creation and Management">
                                                        Content Creation and Management
                                                    </option>
                                                </optgroup>

                                                <optgroup label="Educational and Training Skills">
                                                    <option
                                                        {{ in_array('Teaching and Instruction', optional($user->user_details)->god_given_skills ?? []) ? 'selected' : null }}
                                                        value="Teaching and Instruction">
                                                        Teaching and Instruction
                                                    </option>
                                                    <option
                                                        {{ in_array('Workshop Facilitation', optional($user->user_details)->god_given_skills ?? []) ? 'selected' : null }}
                                                        value="Workshop Facilitation">
                                                        Workshop Facilitation
                                                    </option>
                                                    <option
                                                        {{ in_array('Youth Mentorship and Leadership', optional($user->user_details)->god_given_skills ?? []) ? 'selected' : null }}
                                                        value="Youth Mentorship and Leadership">
                                                        Youth Mentorship and Leadership
                                                    </option>
                                                    <option
                                                        {{ in_array('Life Skills Coaching', optional($user->user_details)->god_given_skills ?? []) ? 'selected' : null }}
                                                        value="Life Skills Coaching">
                                                        Life Skills Coaching
                                                    </option>
                                                </optgroup>

                                                <optgroup label="Creative and Artistic Skills">
                                                    <option
                                                        {{ in_array('Music and Instrument Playing', optional($user->user_details)->god_given_skills ?? []) ? 'selected' : null }}
                                                        value="Music and Instrument Playing">
                                                        Music and Instrument Playing
                                                    </option>
                                                    <option
                                                        {{ in_array('Singing and Vocal Training', optional($user->user_details)->god_given_skills ?? []) ? 'selected' : null }}
                                                        value="Singing and Vocal Training">
                                                        Singing and Vocal Training
                                                    </option>
                                                    <option
                                                        {{ in_array('Drama and Theatrical Arts', optional($user->user_details)->god_given_skills ?? []) ? 'selected' : null }}
                                                        value="Drama and Theatrical Arts">
                                                        Drama and Theatrical Arts
                                                    </option>
                                                    <option
                                                        {{ in_array('Visual Arts and Crafts', optional($user->user_details)->god_given_skills ?? []) ? 'selected' : null }}
                                                        value="Visual Arts and Crafts">
                                                        Visual Arts and Crafts
                                                    </option>
                                                    <option
                                                        {{ in_array('Writing and Editing', optional($user->user_details)->god_given_skills ?? []) ? 'selected' : null }}
                                                        value="Writing and Editing">
                                                        Writing and Editing
                                                    </option>
                                                </optgroup>

                                                <optgroup label="Health and Wellness Skills">
                                                    <option
                                                        {{ in_array('Counseling and Mental Health Support', optional($user->user_details)->god_given_skills ?? []) ? 'selected' : null }}
                                                        value="Counseling and Mental Health Support">
                                                        Counseling and Mental Health Support
                                                    </option>
                                                    <option
                                                        {{ in_array('Health and Fitness Training', optional($user->user_details)->god_given_skills ?? []) ? 'selected' : null }}
                                                        value="Health and Fitness Training">
                                                        Health and Fitness Training
                                                    </option>
                                                    <option
                                                        {{ in_array('First Aid and Medical Support', optional($user->user_details)->god_given_skills ?? []) ? 'selected' : null }}
                                                        value="First Aid and Medical Support">
                                                        First Aid and Medical Support
                                                    </option>
                                                    <option
                                                        {{ in_array('Nutrition and Wellness Coaching', optional($user->user_details)->god_given_skills ?? []) ? 'selected' : null }}
                                                        value="Nutrition and Wellness Coaching">
                                                        Nutrition and Wellness Coaching
                                                    </option>
                                                </optgroup>

                                                <optgroup label="Logistics and Support Skills">
                                                    <option
                                                        {{ in_array('Transportation Coordination', optional($user->user_details)->god_given_skills ?? []) ? 'selected' : null }}
                                                        value="Transportation Coordination">
                                                        Transportation Coordination
                                                    </option>
                                                    <option
                                                        {{ in_array('Food Preparation and Catering', optional($user->user_details)->god_given_skills ?? []) ? 'selected' : null }}
                                                        value="Food Preparation and Catering">
                                                        Food Preparation and Catering
                                                    </option>
                                                    <option
                                                        {{ in_array('etup and Teardown (Event Logistics)', optional($user->user_details)->god_given_skills ?? []) ? 'selected' : null }}
                                                        value="Setup and Teardown (Event Logistics)">
                                                        Setup and Teardown (Event Logistics)
                                                    </option>
                                                    <option
                                                        {{ in_array('Audio/Visual Equipment Management', optional($user->user_details)->god_given_skills ?? []) ? 'selected' : null }}
                                                        value="Audio/Visual Equipment Management">
                                                        Audio/Visual Equipment Management
                                                    </option>
                                                </optgroup>
                                            </select>
                                        </div>
                                    </div>
                                    <!--end col-->

                                    <div class="col-lg-6">
                                        <div class="my-3">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-xs d-block flex-shrink-0 me-3">
                                                    <span class="avatar-title rounded-circle fs-16 bg-body text-body">
                                                        <i class="ri-map-pin-line"></i>
                                                    </span>
                                                </div>
                                                <input type="text" name="address" class="form-control"
                                                    id="address-field" placeholder="Address"
                                                    value="{{ optional($user->user_details)->address }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="my-3 d-flex">
                                            <div class="avatar-xs d-block flex-shrink-0 me-3">
                                                <span class="avatar-title rounded-circle fs-16 bg-primary">
                                                    <i class="ri-facebook-fill"></i>
                                                </span>
                                            </div>
                                            <input type="url" class="form-control" name="facebook_link"
                                                id="facebook-link-field"
                                                value="{{ optional($user->user_details)->facebook_link }}"
                                                placeholder="Facebook Link">
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="my-3 d-flex">
                                            <div class="avatar-xs d-block flex-shrink-0 me-3">
                                                <span class="avatar-title rounded-circle fs-16 bg-danger">
                                                    <i class="ri-instagram-fill"></i>
                                                </span>
                                            </div>
                                            <input type="url" class="form-control" name="instagram_link"
                                                id="instagram-link-field"
                                                value="{{ optional($user->user_details)->instagram_link }}"
                                                placeholder="Instagram Link">
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="my-3 d-flex">
                                            <div class="avatar-xs d-block flex-shrink-0 me-3">
                                                <span class="avatar-title rounded-circle fs-16 bg-dark">
                                                    <i class="ri-twitter-x-fill"></i>
                                                </span>
                                            </div>
                                            <input type="url" class="form-control" id="twitter-link-field"
                                                placeholder="Twitter"
                                                value="{{ optional($user->user_details)->twitter_link }}"
                                                name="twitter_link">
                                        </div>
                                    </div>

                                    <div class="col-lg-12 mt-3">
                                        <div class="hstack gap-2 justify-content-end">
                                            <button type="submit" class="btn btn-primary">Update</button>
                                            <button type="button" class="btn btn-soft-success">Cancel</button>
                                        </div>
                                    </div>
                                    <!--end col-->
                                </div>
                                <!--end row-->
                            </form>
                        </div>
                        <!--end tab-pane-->

                        <!--changePassword-->
                        <div class="tab-pane" id="changePassword" role="tabpanel">
                            <form action="{{ route('users.profile.change_password', $user->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="row g-2">
                                    <div class="col-lg-4">
                                        <div>
                                            <label for="oldpasswordInput" class="form-label">Old
                                                Password*</label>
                                            <input type="password" class="form-control" id="oldpasswordInput"
                                                placeholder="Enter current password" name="old_password">
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-4">
                                        <div>
                                            <label for="newpasswordInput" class="form-label">New
                                                Password*</label>
                                            <input type="password" class="form-control" id="newpasswordInput"
                                                placeholder="Enter new password" name="new_password">
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-4">
                                        <div>
                                            <label for="confirmpasswordInput" class="form-label">Confirm
                                                Password*</label>
                                            <input type="password" class="form-control" id="confirmpasswordInput"
                                                placeholder="Confirm password" name="password_confirmation">
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-12">
                                        <div class="text-end">
                                            <button type="submit" class="btn btn-success">Change
                                                Password</button>
                                        </div>
                                    </div>
                                    <!--end col-->
                                </div>
                                <!--end row-->
                            </form>
                        </div>
                        <!--end tab-pane-->

                        <!--service-->
                        <div class="tab-pane" id="service" role="tabpanel">
                            @if ($errors->any())
                                @push('scripts')
                                    <script>
                                        toastr.error("Multiple entries are invalid or do not match with the required format.", "Submission failed");
                                    </script>
                                @endpush
                            @endif
                            @php
                                $serviceRows = old('service_category') !== null
                                    ? collect(old('service_category', []))->map(function ($category, $index) {
                                        return [
                                            'id' => old('service_ids.' . $index),
                                            'service_category' => $category,
                                            'service_type' => old('service_type.' . $index),
                                            'section' => old('section.' . $index),
                                            'area' => old('service_area.' . $index),
                                        ];
                                    })->values()
                                    : $user->missionary_services->map(function ($service) {
                                        return [
                                            'id' => $service->id,
                                            'service_category' => $service->service_category,
                                            'service_type' => $service->service_type,
                                            'section' => $service->section,
                                            'area' => $service->area,
                                        ];
                                    })->values();
                            @endphp
                            <form action="{{ route('users.profile.services.put', $user->id) }}" method="POST">

                                @csrf
                                @method('PUT')
                                <div id="service-repeater">
                                    @foreach ($serviceRows as $service)
                                        <div class="containerElement mb-3">
                                            <input type="hidden" name="service_ids[]" value="{{ $service['id'] }}">
                                            <div class="row service-container">
                                                <span class="menu-title mb-1">Service</span>
                                                <div class="col-lg-3">
                                                    <div class="mb-3">
                                                        <label class="form-label">MFC/LCSC</label>
                                                        <select name="service_category[]" class="service-category-select form-select">
                                                            <option value="">Select one</option>
                                                            <option value="mfc"
                                                                {{ $service['service_category'] === 'mfc' ? 'selected' : null }}>
                                                                MFC</option>
                                                            <option value="lcsc"
                                                                {{ $service['service_category'] === 'lcsc' ? 'selected' : null }}>
                                                                LCSC</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <div class="mb-3">
                                                        <label class="form-label">Service Type</label>
                                                        <select name="service_type[]" class="service-type-select form-select"
                                                            data-selected="{{ $service['service_type'] ?? '' }}">
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-lg-3">
                                                    <div class="mb-3">
                                                        <label class="form-label">Section/Pillar</label>
                                                        <select name="section[]" class="section-select form-select"
                                                            data-selected="{{ $service['section'] ?? '' }}">
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-lg-3">
                                                    <div class="mb-3">
                                                        <label class="form-label">Area</label>
                                                        <select name="service_area[]" class="service-area-select form-select">
                                                            <option value="">Select Area</option>
                                                            <option value="NCR - North"
                                                                {{ $service['area'] === 'NCR - North' ? 'selected' : null }}>
                                                                NCR - North</option>
                                                            <option value="NCR - South"
                                                                {{ $service['area'] === 'NCR - South' ? 'selected' : null }}>
                                                                NCR - South</option>
                                                            <option value="NCR - East"
                                                                {{ $service['area'] === 'NCR - East' ? 'selected' : null }}>
                                                                NCR - East</option>
                                                            <option value="NCR - Central"
                                                                {{ $service['area'] === 'NCR - Central' ? 'selected' : null }}>
                                                                NCR - Central</option>
                                                            <option value="South Luzon"
                                                                {{ $service['area'] === 'South Luzon' ? 'selected' : null }}>
                                                                South Luzon</option>
                                                            <option value="North & Central Luzon"
                                                                {{ $service['area'] === 'North & Central Luzon' ? 'selected' : null }}>
                                                                North & Central Luzon</option>
                                                            <option value="Visayas"
                                                                {{ $service['area'] === 'Visayas' ? 'selected' : null }}>
                                                                Visayas</option>
                                                            <option value="Mindanao"
                                                                {{ $service['area'] === 'Mindanao' ? 'selected' : null }}>
                                                                Mindanao</option>
                                                            <option value="International"
                                                                {{ $service['area'] === 'International' ? 'selected' : null }}>
                                                                International</option>
                                                            <option value="Baguio"
                                                                {{ $service['area'] === 'Baguio' ? 'selected' : null }}>
                                                                Baguio</option>
                                                            <option value="Palawan"
                                                                {{ $service['area'] === 'Palawan' ? 'selected' : null }}>
                                                                Palawan</option>
                                                            <option value="Batangas"
                                                                {{ $service['area'] === 'Batangas' ? 'selected' : null }}>
                                                                Batangas</option>
                                                            <option value="Laguna"
                                                                {{ $service['area'] === 'Laguna' ? 'selected' : null }}>
                                                                Laguna</option>
                                                            <option value="Pampanga"
                                                                {{ $service['area'] === 'Pampanga' ? 'selected' : null }}>
                                                                Pampanga</option>
                                                            <option value="Tarlac"
                                                                {{ $service['area'] === 'Tarlac' ? 'selected' : null }}>
                                                                Tarlac</option>
                                                            <option value="Other"
                                                                {{ $service['area'] === 'Other' ? 'selected' : null }}>
                                                                Other</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="hstack gap-2 justify-content-end">
                                                    <button type="button" class="btn btn-soft-danger remove-service-btn">Delete</button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="col-lg-12">
                                    <div class="hstack gap-2">
                                        <button type="submit" id="update-service-btn" class="btn btn-success">Update</button>
                                        <button type="button" id="add-service-btn" class="btn btn-primary">Add New</button>
                                    </div>
                                </div>
                                <!--end col-->
                            </form>
                        </div>
                        <!--end tab-pane-->
                    </div>
                </div>
            </div>
        </div>
        <!--end col-->
    </div>
    <!--end row-->
@endsection
@section('script')
    <script src="{{ URL::asset('build/js/pages/profile-setting.init.js') }}"></script>
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", (event) => {
            const mfcTypes = ["Servant Council", "National Coordinator", "Section Coordinator",
                "Provincial Coordinator", "Area Servant", "Chapter Servant", "Unit Servant",
                "Household Servant", "Mission Volunteer", "Full Time"
            ];
            const mfcSections = ["Kids", "Youth", "Singles", "Handmaids", "Servants", "Couples"];
            const lcscTypes = ["LCSC Coordinator", "Pillar Head", "Area Coordinator", "Provincial Coordinator",
                "Full Time", "Mission Volunteer"
            ];
            const lcscSections = ["LCSC", "Live Pure", "Live Life", "Live the Word", "Live Full", "Live the Faith"];
            const serviceAreas = ["NCR - North", "NCR - South", "NCR - East", "NCR - Central", "South Luzon",
                "North & Central Luzon", "Visayas", "Mindanao", "International", "Baguio", "Palawan",
                "Batangas", "Laguna", "Pampanga", "Tarlac", "Other"
            ];
            const serviceRepeater = document.getElementById("service-repeater");
            const addServiceButton = document.getElementById("add-service-btn");

            const populateSelect = (select, items, placeholder, selectedValue = "") => {
                select.innerHTML = "";

                const placeholderOption = document.createElement("option");
                placeholderOption.value = "";
                placeholderOption.textContent = placeholder;
                select.appendChild(placeholderOption);

                items.forEach(item => {
                    const option = document.createElement("option");
                    option.value = item;
                    option.textContent = item;

                    if (selectedValue === item) {
                        option.selected = true;
                    }

                    select.appendChild(option);
                });

                select.value = selectedValue || "";
            };

            const syncServiceRow = (row) => {
                const categorySelect = row.querySelector(".service-category-select");
                const serviceTypeSelect = row.querySelector(".service-type-select");
                const sectionSelect = row.querySelector(".section-select");
                const selectedServiceType = serviceTypeSelect.dataset.selected || serviceTypeSelect.value || "";
                const selectedSection = sectionSelect.dataset.selected || sectionSelect.value || "";
                const selectedCategory = categorySelect.value;
                const typeOptions = selectedCategory === "mfc" ? mfcTypes : selectedCategory === "lcsc" ? lcscTypes : [];
                const sectionOptions = selectedCategory === "mfc" ? mfcSections : selectedCategory === "lcsc" ? lcscSections : [];

                populateSelect(
                    serviceTypeSelect,
                    typeOptions,
                    "Select service type",
                    selectedServiceType
                );
                populateSelect(
                    sectionSelect,
                    sectionOptions,
                    "Select section/pillar",
                    selectedSection
                );

                serviceTypeSelect.dataset.selected = "";
                sectionSelect.dataset.selected = "";
            };

            const buildServiceRow = (service = {}) => {
                const wrapper = document.createElement("div");
                wrapper.className = "containerElement mb-3";
                wrapper.innerHTML = `
                    <input type="hidden" name="service_ids[]" value="${service.id ? service.id : ""}">
                    <div class="row service-container">
                        <span class="menu-title mb-1">Service</span>
                        <div class="col-lg-3">
                            <div class="mb-3">
                                <label class="form-label">MFC/LCSC</label>
                                <select name="service_category[]" class="service-category-select form-select">
                                    <option value="">Select one</option>
                                    <option value="mfc">MFC</option>
                                    <option value="lcsc">LCSC</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="mb-3">
                                <label class="form-label">Service Type</label>
                                <select name="service_type[]" class="service-type-select form-select"></select>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="mb-3">
                                <label class="form-label">Section/Pillar</label>
                                <select name="section[]" class="section-select form-select"></select>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="mb-3">
                                <label class="form-label">Area</label>
                                <select name="service_area[]" class="service-area-select form-select"></select>
                            </div>
                        </div>
                        <div class="hstack gap-2 justify-content-end">
                            <button type="button" class="btn btn-soft-danger remove-service-btn">Delete</button>
                        </div>
                    </div>
                `;

                const categorySelect = wrapper.querySelector(".service-category-select");
                const serviceTypeSelect = wrapper.querySelector(".service-type-select");
                const sectionSelect = wrapper.querySelector(".section-select");
                const serviceAreaSelect = wrapper.querySelector(".service-area-select");

                categorySelect.value = service.service_category || "";
                serviceTypeSelect.dataset.selected = service.service_type || "";
                sectionSelect.dataset.selected = service.section || "";
                populateSelect(serviceAreaSelect, serviceAreas, "Select Area", service.area || "");
                syncServiceRow(wrapper);

                return wrapper;
            };

            serviceRepeater.querySelectorAll(".containerElement").forEach(row => {
                populateSelect(row.querySelector(".service-area-select"), serviceAreas, "Select Area", row.querySelector(
                    ".service-area-select").value || "");
                syncServiceRow(row);
            });

            serviceRepeater.addEventListener("change", (e) => {
                if (e.target.classList.contains("service-category-select")) {
                    const row = e.target.closest(".containerElement");
                    const serviceTypeSelect = row.querySelector(".service-type-select");
                    const sectionSelect = row.querySelector(".section-select");

                    serviceTypeSelect.dataset.selected = "";
                    sectionSelect.dataset.selected = "";
                    syncServiceRow(row);
                }
            });

            serviceRepeater.addEventListener("click", (e) => {
                if (e.target.classList.contains("remove-service-btn")) {
                    e.target.closest(".containerElement").remove();
                }
            });

            addServiceButton.addEventListener("click", () => {
                serviceRepeater.appendChild(buildServiceRow());
            });

            $('#birthday-field').flatpickr({
                enableTime: false,
                altFormat: "F j, Y",
                dateFormat: "Y-m-d",
                maxDate: 'today',
            });

            $("#god-given-skill-select").select2();

        });
    </script>
@endsection
