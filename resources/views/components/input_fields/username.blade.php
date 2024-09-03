@props([
    'id' => 'usernameInput',
    'name' => 'username',
    'value' => '',

])

<div class="mb-3">
    <label for="{{ $id }}" class="form-label">Username<span class="text-danger">*</span></label>
    <input name="{{ $name }}" type="text" class="form-control" id="{{ $id }}" placeholder="Enter username" required
        oninput="this.value = validateUsername(this.value, '{{ $id }}');" value="{{ $value }}">
    <div class="invalid-feedback">Please Enter a username.</div>
</div>
