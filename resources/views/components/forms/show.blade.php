<div class="row mt-5 justify-content-center">
    <div class="col-9">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="fullNameInput" class="form-label">Full Name</label>
                            <input type="text" class="form-control text-capitalize" readonly
                                value="{{ $user->name }}" id="fullNameInput">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="emailInput" class="form-label">Email Address</label>
                            <input type="email" class="form-control" readonly value="{{ $user->email }}"
                                id="emailInput">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="mb-3">
                            <label for="usernameInput" class="form-label">Username</label>
                            <input type="text" class="form-control text-capitalize" readonly
                                value="{{ $user->username ?? 'N/A' }}" id="usernameInput">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="mb-3">
                            <label for="phonenumberInput" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" value="{{ $user->contact_number ?? 'N/A' }}"
                                readonly id="phonenumberInput">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="mb-3">
                            <label class="form-label">Gender</label>
                            <input type="text" class="form-control" value="{{ $user->gender ?? 'N/A' }}" readonly>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="areaInput" class="form-label">Area</label>
                            <input type="text" class="form-control" value="{{ $user->area ?? 'N/A' }}" readonly
                                id="areaInput">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="chapterInput" class="form-label">Chapter</label>
                            <input type="text" class="form-control" value="{{ $user->chapter ?? 'N/A' }}" readonly
                                id="chapterInput">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3">
                            <label class="form-label">Section</label>
                            <input type="text" class="form-control" value="Kids" readonly>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3">
                            <label class="form-label">Servant Name</label>
                            <input type="text" class="form-control" value="{{ $user->servant_id ?? 'N/A' }}"
                                readonly>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea type="text" class="form-control" readonly rows="5">{{ $user->description ?? 'N/A' }}</textarea>
                        </div>
                    </div>
                </div><!--end row-->

                <div class="d-flex justify-content-end mt-3">
                    <a href="javascript:history.back()" class="btn btn-soft-light text-dark">Back</a>
                    <a href="{{ route('users.edit', ['user' => $user->id]) }}" class="btn btn-danger"><span
                            class="ri-pencil-line"></span> Edit</a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-3">
        <div class="card">
            <div class="card-body p-4">
                <div class="text-center">
                    <div class="profile-user position-relative d-inline-block mx-auto  mb-4">
                        <img src="{{ $user->avatar ? URL::asset('build/images/users/' . $user->avatar) : URL::asset('build/images/users/avatar-1.jpg') }}"
                            class="rounded-circle avatar-xl img-thumbnail user-profile-image material-shadow"
                            alt="user-profile-image">
                    </div>
                    <h5 class="fs-16 mb-1 text-capitalize">{{ $user->name }}</h5>
                    <p class="text-muted mb-0">{{ $role ?? 'member' }}</p>
                    <div class="mt-4 row justify-content-between text-muted">
                        <p class="col-6 align-self-center text-start">Joined:</p>
                        <p class="col-6 align-self-center text-end">{{ $joined }}</p>
                        <p class="col-6 align-self-center text-start">Birthday:</p>
                        <p class="col-6 align-self-center text-end">{{ $user->dob ?? 'N/A' }}</p>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
