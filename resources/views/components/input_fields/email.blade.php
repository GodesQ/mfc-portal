@props([
    'id' => 'emailInput',
    'name' => 'email',
    'value' => '',
])

<div class="mb-3">
    <label for="{{ $id }}" class="form-label">Email <span class="text-danger">*</span></label>
    <input name="{{ $name }}" type="email" class="form-control" id="{{ $id }}" placeholder="jdc@example.com" required
        oninput="validateEmail(this.value, '{{ $id }}')" value="{{ $value }}">
    <div class="invalid-feedback">Please Enter a valid email.</div>
</div>
