@props([
    'color' => '',
    'route' => '',
    'sections' => '',
])

<div class="modal fade" id="user-modal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header p-3 bg-info-subtle">
                <h5 class="modal-title" id="modal-title">Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <form class="user-form" id="edit-user-form" action="#">
                    @csrf
                    <input type="hidden" id="user-id-field-edit">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="first-name-field-edit" class="form-label">First Name</label>
                                <input type="text" class="form-control" name="first_name" id="first-name-field-edit">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="last-name-field-edit" class="form-label">Last Name</label>
                                <input type="text" class="form-control" name="last_name" id="last-name-field-edit">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="username-field-edit" class="form-label">Username</label>
                                <input type="text" class="form-control" name="username" id="username-field-edit">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="email-field-edit" class="form-label">Email</label>
                                <input type="text" class="form-control" name="email" id="email-field-edit">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="contact-number-field-edit" class="form-label">Contact Number</label>
                                <input type="text" class="form-control" name="contact_number"
                                    id="contact-number-field-edit">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="section-field-edit" class="form-label">Section</label>
                                <select name="section_id" id="section-field-edit" class="form-select">
                                    @foreach ($sections as $section)
                                        <option value="{{ $section->id }}">{{ $section->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="role-field-edit" class="form-label">Role</label>
                                <select name="role_id" id="role-field-edit" class="form-select">
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-end align-items-center">
                        <button type="submit" class="btn btn-primary">Update User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $("#edit-user-form").on('submit', (e) => {
        e.preventDefault();
        let formData = new FormData(e.target);

        formData.append('_method', 'PUT'); // Laravel needs this to treat it as a PUT request
        formData.append('_token', document.querySelector('input[name="_token"]').value);
        let user_id = $('#user-id-field-edit').val();

        $.ajax({
            url: `/dashboard/users/${user_id}`,
            method: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                toastr.success(response.message, "Success");
            }
        })
    })
</script>
