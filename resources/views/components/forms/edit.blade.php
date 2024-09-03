<style>
    .flatpickr-calendar:before,
    .flatpickr-calendar:after {
        position: absolute;
        display: none !important;
        pointer-events: none;
        border: solid transparent;
        content: "";
        height: 0;
        width: 0;
        left: 22px;
    }
</style>

<form autocomplete="off" id="userEditForm" class="needs-validation" novalidate action="{{ $route }}" method="POST"
    enctype="multipart/form-data">
    @csrf
    @method('PATCH')
    <div class="row mt-5 justify-content-center">
        <div class="col-9">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            @component('components.input_fields.name')
                                @slot('value')
                                    {{ $user->name }}
                                @endslot
                            @endcomponent
                        </div>
                        <div class="col-6">
                            @component('components.input_fields.email')
                                @slot('value')
                                    {{ $user->email }}
                                @endslot
                            @endcomponent
                        </div>
                        <div class="col-4">
                            @component('components.input_fields.username')
                                @slot('value')
                                    {{ $user->username }}
                                @endslot
                            @endcomponent
                        </div>
                        <div class="col-4">
                            @component('components.input_fields.contact-number')
                                @slot('value')
                                    {{ $user->contact_number }}
                                @endslot
                            @endcomponent
                        </div>
                        <div class="col-4">
                            @component('components.input_fields.choices')
                                @slot('label')
                                    gender
                                @endslot
                                @slot('formId')
                                    userEditForm
                                @endslot

                                <option value="Brother" {{ $user->gender == 'Brother' ? 'selected' : '' }}>Brother</option>
                                <option value="Sister" {{ $user->gender == 'Sister' ? 'selected' : '' }}>Sister</option>
                            @endcomponent
                        </div>
                        <div class="col-6">
                            @component('components.input_fields.choices')
                                @slot('label')
                                    area
                                @endslot
                                @slot('formId')
                                    userEditForm
                                @endslot

                                <option value="North" {{ $user->area == 'North' ? 'selected' : '' }}>North</option>
                                <option value="East" {{ $user->area == 'East' ? 'selected' : '' }}>East</option>
                                <option value="Central" {{ $user->area == 'Central' ? 'selected' : '' }}>Central</option>
                                <option value="South" {{ $user->area == 'South' ? 'selected' : '' }}>South</option>
                            @endcomponent
                        </div>
                        <div class="col-6">
                            @component('components.input_fields.choices')
                                @slot('label')
                                    chapter
                                @endslot
                                @slot('formId')
                                    userEditForm
                                @endslot

                                <option value="Chapter 1" {{ $user->chapter == 'Chapter 1' ? 'selected' : '' }}>Chapter 1
                                </option>
                                <option value="Chapter 2" {{ $user->chapter == 'Chapter 2' ? 'selected' : '' }}>Chapter 2
                                </option>
                                <option value="Chapter 3" {{ $user->chapter == 'Chapter 3' ? 'selected' : '' }}>Chapter 3
                                </option>
                                <option value="Others" {{ $user->chapter == 'Others' ? 'selected' : '' }}>Others</option>
                            @endcomponent
                        </div>
                        <div class="col-6">
                            @component('components.input_fields.choices')
                                @slot('label')
                                    section
                                @endslot
                                @slot('formId')
                                    userEditForm
                                @endslot

                                <option value="Kids" {{ $user->section_id == '1' ? 'selected' : '' }}>Kids
                                </option>
                                <option value="Youth" {{ $user->section_id == 'Youth' ? 'selected' : '' }}>Youth
                                </option>
                                <option value="Singles" {{ $user->section_id == 'Singles' ? 'selected' : '' }}>Singles
                                </option>
                                <option value="Handmaids" {{ $user->section_id == 'Handmaids' ? 'selected' : '' }}>
                                    Handmaids
                                </option>
                                <option value="Servants" {{ $user->section_id == 'Servants' ? 'selected' : '' }}>Servants
                                </option>
                                <option value="Couples" {{ $user->section_id == 'Couples' ? 'selected' : '' }}>Couples
                                </option>
                            @endcomponent
                        </div>
                        <div class="col-6">
                            @component('components.input_fields.name')
                                @slot('id')
                                    servantNameInput
                                @endslot
                                @slot('name')
                                    servant_name
                                @endslot
                                @slot('label')
                                    Servant Name
                                @endslot
                                @slot('value')
                                    {{ $user->servant_id }}
                                @endslot
                            @endcomponent
                        </div>
                        <div class="col-12">
                            @component('components.input_fields.textarea')
                                @slot('label')
                                    Description
                                @endslot
                            @endcomponent
                        </div>
                    </div><!--end row-->

                    <div class="d-flex justify-content-end mt-3">
                        <a href="javascript:history.back()" class="btn btn-soft-light text-dark">Back</a>
                        <button type="submit" class="btn btn-danger"><span class="ri-pencil-line"></span>
                            Update</button>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-3">
            <div class="card">
                <div class="card-body p-4">
                    <div class="text-center">
                        <div class="profile-user position-relative d-inline-block mx-auto  mb-4">
                            <img src="{{ $user->avatar ? URL::asset('build/images/users/' . $user->avatar . '') : URL::asset('build/images/users/avatar-2.jpg') }}"
                                class="rounded-circle avatar-xl img-thumbnail user-profile-image material-shadow"
                                alt="user-profile-image" id="user-profile-image">
                            <div class="avatar-xs p-0 rounded-circle profile-photo-edit">
                                <input id="profile-img-file-input" name="avatar" type="file"
                                    class="profile-img-file-input" hidden accept="image/png, image/jpeg, image/jpg">
                                <label for="profile-img-file-input" class="profile-photo-edit avatar-xs">
                                    <span class="avatar-title rounded-circle bg-light text-body material-shadow">
                                        <i class="ri-camera-fill"></i>
                                    </span>
                                </label>
                            </div>
                        </div>

                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                imageInput({
                                    inputId: 'profile-img-file-input',
                                    imgId: 'user-profile-image'
                                });
                            });
                        </script>

                        <h5 class="fs-16 mb-1 text-capitalize">{{ $user->name }}</h5>
                        <p class="text-muted mb-0">{{ $role ?? 'member' }}</p>
                        <div class="mt-4 row justify-content-between text-muted">
                            <p class="col-6 align-self-center text-start">Joined:</p>
                            <p class="col-6 align-self-center text-end">{{ $joined }}</p>
                            <div class="col-12 d-flex justify-content-between">
                                <p class="align-self-center text-start">Birthday:</p>
                                <label class="d-flex gap-2 justify-content-end" style="cursor: pointer;" id="calendar"
                                    for="calendarInput">
                                    <input type="text" name="dob"
                                        class="form-control text-muted flatpickr-input w-50 border-0 bg-transparent position-absolute text-end"
                                        id="calendarInput" style="top: -7px; right: 15px;" data-provider="flatpickr"
                                        data-date-format="F d, Y" value="{{ $dob ?? 'N/A' }}">
                                    <span class="ri-calendar-line text-secondary"></span>
                                </label>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
