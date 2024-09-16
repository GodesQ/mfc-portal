@props([
    'color' => '',
    'route' => '',
])

<div class="modal fade" id="addmemberModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-body">
                <form autocomplete="off" id="memberlist-form" action="{{ $route }}"
                    method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-lg-12">
                            <input type="hidden" id="memberid-input" class="form-control" value="">
                            <div class="px-1 pt-1">
                                <div
                                    class="modal-team-cover position-relative mb-0 mt-n4 mx-n4 rounded-top overflow-hidden">
                                    <img src="{{ URL::asset('build/images/small/img-9.jpg') }}" alt=""
                                        id="cover-img" class="img-fluid">

                                    <div class="d-flex position-absolute start-0 end-0 top-0 p-3">
                                        <div class="flex-grow-1">
                                            <h5 class="modal-title text-white" id="createMemberLabel">Add
                                                New Member</h5>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <div class="d-flex gap-3 align-items-center">
                                                <div>
                                                    <label for="cover-image-input" class="mb-0"
                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                        title="Select Cover Image">
                                                        <div class="avatar-xs">
                                                            <div
                                                                class="avatar-title bg-light border rounded-circle text-muted cursor-pointer">
                                                                <i class="ri-image-fill"></i>
                                                            </div>
                                                        </div>
                                                    </label>
                                                    <input class="form-control d-none" value=""
                                                        id="cover-image-input" type="file"
                                                        accept="image/png, image/gif, image/jpeg">
                                                </div>
                                                <button type="button" class="btn-close btn-close-white"
                                                    id="createMemberBtn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center mb-4 mt-n5 pt-2">
                                <div class="position-relative d-inline-block">
                                    <div class="position-absolute bottom-0 end-0">
                                        <label for="member-image-input" class="mb-0" data-bs-toggle="tooltip"
                                            data-bs-placement="right" title="Select Member Image">
                                            <div class="avatar-xs">
                                                <div
                                                    class="avatar-title bg-light border rounded-circle text-muted cursor-pointer">
                                                    <i class="ri-image-fill"></i>
                                                </div>
                                            </div>
                                        </label>
                                        <input class="form-control d-none" value="" id="member-image-input"
                                            type="file" accept="image/png, image/gif, image/jpeg">
                                    </div>
                                    <div class="avatar-lg">
                                        <div class="avatar-title bg-light rounded-circle">
                                            <img src="{{ URL::asset('build/images/users/user-dummy-img.jpg') }}"
                                                id="member-img" class="avatar-md rounded-circle h-auto" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="first-name-field" class="form-label">First Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="first_name" id="first-name-field" required>
                            </div>
                            <div class="mb-3">
                                <label for="last-name-field" class="form-label">Last Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="last_name" id="last-name-field" required>
                            </div>
                            
                            @component('components.input_fields.username')
                                @slot('id')
                                    username
                                @endslot
                                @slot('name')
                                    username
                                @endslot
                            @endcomponent

                            @component('components.input_fields.email')
                                @slot('id')
                                    email
                                @endslot
                                @slot('name')
                                    email
                                @endslot
                            @endcomponent

                            <div class="mb-3">
                                <label for="password-field" class="form-label">Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" required name="password" id="password-field">
                            </div>

                            <div class="mb-3">
                                <label for="section-field">Section <span class="text-danger">*</span></label>
                                <select name="section_id" id="section-field" class="form-select" required>
                                    <option {{ Request::is('dashboard/directory/kids') ? "selected" : null }} value="1">Kids</option>
                                    <option {{ Request::is('dashboard/directory/youth') ? "selected" : null }} value="2">Youth</option>
                                    <option {{ Request::is('dashboard/directory/singles') ? "selected" : null }} value="3">Singles</option>
                                    <option {{ Request::is('dashboard/directory/handmaids') ? "selected" : null }} value="4">Handmaids</option>
                                    <option {{ Request::is('dashboard/directory/servants') ? "selected" : null }} value="5">Servants</option>
                                    <option {{ Request::is('dashboard/directory/couples') ? "selected" : null }} value="6">Couples</option>
                                </select>
                            </div>

                            <div class="hstack gap-2 justify-content-end">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn {{ $color }}" id="addNewMember">Add
                                    Member</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!--end modal-content-->
    </div>
    <!--end modal-dialog-->
</div>
