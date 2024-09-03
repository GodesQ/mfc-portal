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
                    <div class="p-0 ms-auto rounded-circle profile-photo-edit">
                        <input id="profile-foreground-img-file-input" type="file"
                            class="profile-foreground-img-file-input">
                        <label for="profile-foreground-img-file-input" class="profile-photo-edit btn btn-light">
                            <i class="ri-image-edit-line align-bottom me-1"></i> Change Cover
                        </label>
                    </div>
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
                            <img src="@if (Auth::user()->avatar != '') {{ URL::asset('build/images/users/' . Auth::user()->avatar) }}@else{{ URL::asset('build/images/users/avatar-1.jpg') }} @endif"
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
                        <h5 class="fs-16 mb-1">{{ Auth::user()->fist_name ?? 'Sample' }}
                            {{ Auth::user()->last_name ?? 'User' }}</h5>
                        <p class="text-muted mb-0">MFC ID: <span id="mfc-id">{{ Auth::user()->mfc_id_number }}</span> <a
                                href="javascript:void(0);" class="ri-file-copy-line" id="copy-mfc-id"
                                data-bs-toggle="tooltip" data-bs-placement="right"></a></p>
                        <script>
                            document.getElementById('copy-mfc-id').addEventListener('click', function(event) {
                                event.preventDefault(); // Prevent default action
                                console.log('test');

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
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-5">
                        <div class="flex-grow-1">
                            <h5 class="card-title mb-0">Complete Your Profile</h5>
                        </div>
                        <div class="flex-shrink-0">
                            <a href="javascript:void(0);" onclick="editProfile('firstnameInput');"
                                class="badge bg-light text-primary fs-12"><i class="ri-edit-box-line align-bottom me-1"></i>
                                Edit</a>
                        </div>
                    </div>
                    <div class="progress animated-progress custom-progress progress-label">
                        <div class="progress-bar bg-danger" role="progressbar" style="width: 30%" aria-valuenow="30"
                            aria-valuemin="0" aria-valuemax="100">
                            <div class="label">30%</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <div class="flex-grow-1">
                            <h5 class="card-title mb-0">Other Infos</h5>
                        </div>
                    </div>
                    <div class="mb-3 d-flex">
                        <div class="avatar-xs d-block flex-shrink-0 me-3">
                            <span class="avatar-title rounded-circle fs-16 bg-body text-body">
                                <i class="ri-map-pin-line"></i>
                            </span>
                        </div>
                        <input type="email" class="form-control" id="userAddress" placeholder="Address"
                            value="123 sample St., Quezon City">
                    </div>
                    <div class="mb-3 d-flex">
                        <div class="avatar-xs d-block flex-shrink-0 me-3">
                            <span class="avatar-title rounded-circle fs-16 bg-primary">
                                <i class="ri-facebook-fill"></i>
                            </span>
                        </div>
                        <input type="text" class="form-control" id="websiteInput" placeholder="www.example.com"
                            value="@facebookUser">
                    </div>
                    <div class="mb-3 d-flex">
                        <div class="avatar-xs d-block flex-shrink-0 me-3">
                            <span class="avatar-title rounded-circle fs-16 bg-danger">
                                <i class="ri-instagram-fill"></i>
                            </span>
                        </div>
                        <input type="text" class="form-control" id="dribbleName" placeholder="Username"
                            value="@instagramUser">
                    </div>
                    <div class="d-flex">
                        <div class="avatar-xs d-block flex-shrink-0 me-3">
                            <span class="avatar-title rounded-circle fs-16 bg-dark">
                                <i class="ri-twitter-x-fill"></i>
                            </span>
                        </div>
                        <input type="text" class="form-control" id="pinterestName" placeholder="Username"
                            value="@sampleUser">
                    </div>
                </div>
            </div>
            <!--end card-->
        </div>
        <!--end col-->
        <div class="col-xxl-9">
            <div class="card mt-xxl-n5">
                <div class="card-header">
                    <ul class="nav nav-tabs-custom rounded card-header-tabs border-bottom-0" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#personalDetails" role="tab">
                                <i class="fas fa-home"></i>
                                Personal Details
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#changePassword" role="tab">
                                <i class="far fa-user"></i>
                                Change Password
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#service" role="tab">
                                <i class="far fa-envelope"></i>
                                Service
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body p-4">
                    <div class="tab-content">
                        <!-- personalDetails -->
                        <div class="tab-pane active" id="personalDetails" role="tabpanel">
                            <form action="{{ route('users.profile.update', $user->id) }}" method="POST">
                                @csrf
                                @method('PUT')
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
                                                placeholder="Enter your lastname" value="{{ $user->last_name }}">
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="phonenumberInput" class="form-label">Phone
                                                Number</label>
                                            <input name="contact_number" type="text" class="form-control" id="phonenumberInput"
                                                placeholder="Enter your phone number" value="{{ $user->contact_number }}">
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="emailInput" class="form-label">Email
                                                Address</label>
                                            <input type="email" name="email" class="form-control" id="emailInput"
                                                placeholder="Enter your email" value="{{ $user->email }}">
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
                                        <div class="mb-3">
                                            <label for="JoiningdatInput" class="form-label">MFC Section</label>
                                            <select name="section" id="mfc_section" class="form-select">
                                                <option value="">Select Section</option>
                                                @foreach ($sections as $section)
                                                    <option value="{{ $section->id }}"
                                                        {{ $section->id == $user->section_id ? 'selected' : null }}>
                                                        {{ $section->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
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
                                                <option {{ $user->area == 'handmaids' ? 'selected' : null }}
                                                    value="handmaids">South Luzon</option>
                                                <option {{ $user->area == 'servants' ? 'selected' : null }}
                                                    value="servants">North & Central Luzon</option>
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
                                            <label for="skillsInput" class="form-label">God-given Skills</label>
                                            <select class="form-select" name="god_given_skill"
                                                id="skillsInput">
                                                <option value="">Select a Skill</option>
                                                <optgroup label="Spiritual and Pastoral Skills">
                                                    <option
                                                        {{ optional($user->user_details)->god_given_skill == 'Prayer Leading' ? 'selected' : null }}
                                                        value="Prayer Leading">
                                                        Prayer Leading
                                                    </option>
                                                    <option
                                                        {{ optional($user->user_details)->god_given_skill == 'Bible Study Facilitation' ? 'selected' : null }}
                                                        value="Bible Study Facilitation">
                                                        Bible Study Facilitation
                                                    </option>
                                                    <option
                                                        {{ optional($user->user_details)->god_given_skill == 'Spiritual Counseling' ? 'selected' : null }}
                                                        value="Spiritual Counseling">
                                                        Spiritual Counseling
                                                    </option>
                                                    <option
                                                        {{ optional($user->user_details)->god_given_skill == 'Worship Leading' ? 'selected' : null }}
                                                        value="Worship Leading">
                                                        Worship Leading
                                                    </option>
                                                    <option
                                                        {{ optional($user->user_details)->god_given_skill == 'Catechism Teaching' ? 'selected' : null }}
                                                        value="Catechism Teaching">
                                                        Catechism Teaching
                                                    </option>
                                                </optgroup>

                                                <optgroup label="Community and Social Skills">
                                                    <option
                                                        {{ optional($user->user_details)->god_given_skill == 'Event Planning and Coordination' ? 'selected' : null }}
                                                        value="Event Planning and Coordination">
                                                        Event Planning and Coordination
                                                    </option>
                                                    <option
                                                        {{ optional($user->user_details)->god_given_skill == 'Community Outreach' ? 'selected' : null }}
                                                        value="Community Outreach">
                                                        Community Outreach
                                                    </option>
                                                    <option
                                                        {{ optional($user->user_details)->god_given_skill == 'Fundraising and Development' ? 'selected' : null }}
                                                        value="Fundraising and Development">
                                                        Fundraising and Development
                                                    </option>
                                                    <option
                                                        {{ optional($user->user_details)->god_given_skill == 'Volunteer Management' ? 'selected' : null }}
                                                        value="Volunteer Management">
                                                        Volunteer Management
                                                    </option>
                                                    <option
                                                        {{ optional($user->user_details)->god_given_skill == 'Conflict Resolution' ? 'selected' : null }}
                                                        value="Conflict Resolution">
                                                        Conflict Resolution
                                                    </option>
                                                </optgroup>

                                                <optgroup label="Administrative and Technical Skills">
                                                    <option
                                                        {{ optional($user->user_details)->god_given_skill == 'Administration and Office Management' ? 'selected' : null }}
                                                        value="Administration and Office Management">
                                                        Administration and Office Management
                                                    </option>
                                                    <option
                                                        {{ optional($user->user_details)->god_given_skill == 'Financial Management and Accounting' ? 'selected' : null }}
                                                        value="Financial Management and Accounting">
                                                        Financial Management and Accounting
                                                    </option>
                                                    <option
                                                        {{ optional($user->user_details)->god_given_skill == 'IT Support and Systems Management' ? 'selected' : null }}
                                                        value="IT Support and Systems Management">
                                                        IT Support and Systems Management
                                                    </option>
                                                    <option
                                                        {{ optional($user->user_details)->god_given_skill == 'Website Development and Maintenance' ? 'selected' : null }}
                                                        value="Website Development and Maintenance">
                                                        Website Development and Maintenance
                                                    </option>
                                                    <option
                                                        {{ optional($user->user_details)->god_given_skill == 'Graphic Design' ? 'selected' : null }}
                                                        value="Graphic Design">
                                                        Graphic Design
                                                    </option>
                                                    <option
                                                        {{ optional($user->user_details)->god_given_skill == 'Content Creation and Management' ? 'selected' : null }}
                                                        value="Content Creation and Management">
                                                        Content Creation and Management
                                                    </option>
                                                </optgroup>

                                                <optgroup label="Educational and Training Skills">
                                                    <option
                                                        {{ optional($user->user_details)->god_given_skill == 'Teaching and Instruction' ? 'selected' : null }}
                                                        value="Teaching and Instruction">
                                                        Teaching and Instruction
                                                    </option>
                                                    <option
                                                        {{ optional($user->user_details)->god_given_skill == 'Workshop Facilitation' ? 'selected' : null }}
                                                        value="Workshop Facilitation">
                                                        Workshop Facilitation
                                                    </option>
                                                    <option
                                                        {{ optional($user->user_details)->god_given_skill == 'Youth Mentorship and Leadership' ? 'selected' : null }}
                                                        value="Youth Mentorship and Leadership">
                                                        Youth Mentorship and Leadership
                                                    </option>
                                                    <option
                                                        {{ optional($user->user_details)->god_given_skill == 'Life Skills Coaching' ? 'selected' : null }}
                                                        value="Life Skills Coaching">
                                                        Life Skills Coaching
                                                    </option>
                                                </optgroup>

                                                <optgroup label="Creative and Artistic Skills">
                                                    <option
                                                        {{ optional($user->user_details)->god_given_skill == 'Music and Instrument Playing' ? 'selected' : null }}
                                                        value="Music and Instrument Playing">
                                                        Music and Instrument Playing
                                                    </option>
                                                    <option
                                                        {{ optional($user->user_details)->god_given_skill == 'Singing and Vocal Training' ? 'selected' : null }}
                                                        value="Singing and Vocal Training">
                                                        Singing and Vocal Training
                                                    </option>
                                                    <option
                                                        {{ optional($user->user_details)->god_given_skill == 'Drama and Theatrical Arts' ? 'selected' : null }}
                                                        value="Drama and Theatrical Arts">
                                                        Drama and Theatrical Arts
                                                    </option>
                                                    <option
                                                        {{ optional($user->user_details)->god_given_skill == 'Visual Arts and Crafts' ? 'selected' : null }}
                                                        value="Visual Arts and Crafts">
                                                        Visual Arts and Crafts
                                                    </option>
                                                    <option
                                                        {{ optional($user->user_details)->god_given_skill == 'Writing and Editing' ? 'selected' : null }}
                                                        value="Writing and Editing">
                                                        Writing and Editing
                                                    </option>
                                                </optgroup>

                                                <optgroup label="Health and Wellness Skills">
                                                    <option
                                                        {{ optional($user->user_details)->god_given_skill == 'Counseling and Mental Health Support' ? 'selected' : null }}
                                                        value="Counseling and Mental Health Support">
                                                        Counseling and Mental Health Support
                                                    </option>
                                                    <option
                                                        {{ optional($user->user_details)->god_given_skill == 'Health and Fitness Training' ? 'selected' : null }}
                                                        value="Health and Fitness Training">
                                                        Health and Fitness Training
                                                    </option>
                                                    <option
                                                        {{ optional($user->user_details)->god_given_skill == 'First Aid and Medical Support' ? 'selected' : null }}
                                                        value="First Aid and Medical Support">
                                                        First Aid and Medical Support
                                                    </option>
                                                    <option
                                                        {{ optional($user->user_details)->god_given_skill == 'Nutrition and Wellness Coaching' ? 'selected' : null }}
                                                        value="Nutrition and Wellness Coaching">
                                                        Nutrition and Wellness Coaching
                                                    </option>
                                                </optgroup>

                                                <optgroup label="Logistics and Support Skills">
                                                    <option
                                                        {{ optional($user->user_details)->god_given_skill == 'Transportation Coordination' ? 'selected' : null }}
                                                        value="Transportation Coordination">
                                                        Transportation Coordination
                                                    </option>
                                                    <option
                                                        {{ optional($user->user_details)->god_given_skill == 'Food Preparation and Catering' ? 'selected' : null }}
                                                        value="Food Preparation and Catering">
                                                        Food Preparation and Catering
                                                    </option>
                                                    <option
                                                        {{ optional($user->user_details)->god_given_skill == 'Setup and Teardown (Event Logistics)' ? 'selected' : null }}
                                                        value="Setup and Teardown (Event Logistics)">
                                                        Setup and Teardown (Event Logistics)
                                                    </option>
                                                    <option
                                                        {{ optional($user->user_details)->god_given_skill == 'Audio/Visual Equipment Management' ? 'selected' : null }}
                                                        value="Audio/Visual Equipment Management">
                                                        Audio/Visual Equipment Management
                                                    </option>
                                                </optgroup>
                                            </select>
                                        </div>
                                    </div>
                                    <!--end col-->

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="facebook-link-field" class="form-label">Facebook Link</label>
                                            <input type="url" class="form-control" name="facebook_link" id="facebook-link-field" 
                                                value="{{ optional($user->user_details)->facebook_link }}">
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="instagram-link-field" class="form-label">Instagram Link</label>
                                            <input type="url" class="form-control" name="instagram_link" id="instagram-link-field"
                                                value="{{ optional($user->user_details)->instagram_link }}">
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
                                @method("PUT")
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
                                        <div class="mb-3">
                                            <a href="javascript:void(0);"
                                                class="link-primary text-decoration-underline">Forgot
                                                Password ?</a>
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
                            <form action="{{ route('users.profile.services.put', auth()->user()->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div id="newlink">
                                    @forelse ($user->missionary_services as $service)
                                        <div id="1" class="containerElement">
                                            <div class="row service-container">
                                                <span class="menu-title mb-1">Service</span>
                                                <div class="col-3">
                                                    <div class="mb-3">
                                                        <label for="service_category1" class="form-label">MFC/LCSC</label>
                                                        <select name="service_category[]" id="service_category1"
                                                            data-choices data-choices-search-false
                                                            data-choices-sorting-false class="service-category-select">
                                                            <option
                                                                {{ $service->service_category == '' ? 'selected' : null }}
                                                                value="">Select one</option>
                                                            <option
                                                                {{ $service->service_category == 'mfc' ? 'selected' : null }}
                                                                value="mfc" value="{{ $service->service_category }}">
                                                                MFC</option>
                                                            <option
                                                                {{ $service->service_category == 'lcsc' ? 'selected' : null }}
                                                                value="lcsc">LCSC</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-3" id="service_type_container">
                                                    <div class="mb-3">
                                                        <label for="service_type1" class="form-label">Service Type</label>
                                                        <select name="service_type[]" id="service_type1"
                                                            selected-data="{{ $service->service_type }}"
                                                            class="service-type-select form-select">
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-3" id="section_container">
                                                    <div class="mb-3">
                                                        <label for="section1" class="form-label">Section/Pillar</label>
                                                        <select name="section[]" id="section1"
                                                            selected-data="{{ $service->section }}"
                                                            class="section-select form-select">
                                                        </select>
                                                    </div>
                                                </div>
                                                <!--end col-->

                                                <div class="col-lg-3">
                                                    <div class="mb-3">
                                                        <label for="service_area1" class="form-label">Area</label>
                                                        <select name="service_area[]" id="service_area1" data-choices
                                                            data-choices-sorting-false class="sercive-area-select">
                                                            <option {{ $service->area == '' ? 'selected' : null }}
                                                                value="">Select Area</option>
                                                            <option
                                                                {{ $service->area == 'NCR - North' ? 'selected' : null }}
                                                                value="NCR - North">NCR - North</option>
                                                            <option
                                                                {{ $service->area == 'NCR - South' ? 'selected' : null }}
                                                                value="NCR - South">NCR - South</option>
                                                            <option
                                                                {{ $service->area == 'NCR - East' ? 'selected' : null }}
                                                                value="NCR - East">NCR - East</option>
                                                            <option
                                                                {{ $service->area == 'NCR - Central' ? 'selected' : null }}
                                                                value="NCR - Central">NCR - Central</option>
                                                            <option
                                                                {{ $service->area == 'South Luzon' ? 'selected' : null }}
                                                                value="South Luzon">South Luzon</option>
                                                            <option
                                                                {{ $service->area == 'North & Central Luzon' ? 'selected' : null }}
                                                                value="North & Central Luzon">North & Central Luzon
                                                            </option>
                                                            <option {{ $service->area == 'Visayas' ? 'selected' : null }}
                                                                value="Visayas">Visayas</option>
                                                            <option {{ $service->area == 'Mindanao' ? 'selected' : null }}
                                                                value="Mindanao">Mindanao</option>
                                                            <option
                                                                {{ $service->area == 'International' ? 'selected' : null }}
                                                                value="International">International</option>
                                                            <option {{ $service->area == 'Baguio' ? 'selected' : null }}
                                                                value="Baguio">Baguio</option>
                                                            <option {{ $service->area == 'Palawan' ? 'selected' : null }}
                                                                value="Palawan">Palawan</option>
                                                            <option {{ $service->area == 'Batangas' ? 'selected' : null }}
                                                                value="Batangas">Batangas</option>
                                                            <option {{ $service->area == 'Laguna' ? 'selected' : null }}
                                                                value="Laguna">Laguna</option>
                                                            <option {{ $service->area == 'Pampanga' ? 'selected' : null }}
                                                                value="Pampanga">Pampanga</option>
                                                            <option {{ $service->area == 'Tarlac' ? 'selected' : null }}
                                                                value="Tarlac">Tarlac</option>
                                                            <option {{ $service->area == 'Other' ? 'selected' : null }}
                                                                value="Other">Other</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="hstack gap-2 justify-content-end">
                                                    <a class="btn btn-success" href="javascript:deleteEl(1)">Delete</a>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                    @endforelse
                                </div>
                                <div id="newForm" style="display: none;"></div>
                                <div class="col-lg-12">
                                    <div class="hstack gap-2">
                                        <button type="submit" class="btn btn-success">Update</button>
                                        <a href="javascript:new_link()" class="btn btn-primary">Add
                                            New</a>
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
            let mfcTypes = ["Servant Council", "National Coordinator", "Section Coordinator",
                "Provincial Coordinator", "Area Servant", "Chapter Servant", "Unit Servant",
                "Household Servant",
                "Mission Volunteer", "Full Time"
            ];
            let mfcSections = ["Kids", "Youth", "Singles", "Handmaids", "Servants", "Couples"];

            let lcscTypes = ["LCSC Coordinator", "Pillar Head", "Area Coordinator", "Provincial Coordinator",
                "Full Time", "Mission Volunteer"
            ];

            let lcscSections = ["LCSC", "Live Pure", "Live Life", "Live the Word", "Live Full", "Live the Faith"];
            let serviceTypeSelects = document.querySelectorAll(".service-type-select");
            let sectionSelects = document.querySelectorAll(".section-select");

            serviceTypeSelects.forEach(select => {
                let selectedValue = select.getAttribute("selected-data");
                let container = $(select).closest(".containerElement");
                let serviceCategorySelect = container.find('.service-category-select');
                let serviceCategoryValue = serviceCategorySelect[0].value;

                if (serviceCategoryValue === "mfc") {
                    mfcTypes.forEach(type => {
                        var option = document.createElement("option");
                        option.text = type;
                        option.value = type;
                        if (selectedValue == type) {
                            option.setAttribute("selected", true);
                        }
                        select.add(option);
                    })
                } else {
                    lcscTypes.forEach(type => {
                        var option = document.createElement("option");
                        option.text = type;
                        option.value = type;
                        if (selectedValue == type) {
                            option.setAttribute("selected", true);
                        }
                        select.add(option);
                    })
                }
            });

            sectionSelects.forEach(select => {
                let selectedValue = select.getAttribute("selected-data");
                let container = $(select).closest(".containerElement");
                let serviceCategorySelect = container.find('.service-category-select');
                let serviceCategoryValue = serviceCategorySelect[0].value;

                if (serviceCategoryValue === "mfc") {
                    mfcSections.forEach(type => {
                        var option = document.createElement("option");
                        option.text = type;
                        option.value = type;
                        if (selectedValue == type) {
                            option.setAttribute("selected", true);
                        }
                        select.add(option);
                    })
                } else {
                    lcscSections.forEach(type => {
                        var option = document.createElement("option");
                        option.text = type;
                        option.value = type;
                        if (selectedValue == type) {
                            option.setAttribute("selected", true);
                        }
                        select.add(option);
                    })
                }
            });

        });
    </script>
@endsection
