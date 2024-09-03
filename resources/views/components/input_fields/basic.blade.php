@props([
    'id' => 'nameInput',
    'name' => 'name',
    'value' => '',
    'label' => 'Name',
    'feedback' => 'Please Enter a valid name.',
    'placeholder' => '',
])


<div class="mb-3">
    <label for='{{ $id }}' class="form-label">{{ $label }} <span class="text-danger">*</span></label>
    <input name='{{ $name }}' type="text" class="form-control text-capitalize" id='{{ $id }}' placeholder="{{ $placeholder }}" required
        oninput="this.value = validateInput(this.value, '{{ $id }}');" value="{{ $value }}">
    <div class="invalid-feedback">{{ $feedback }}</div>
</div>
