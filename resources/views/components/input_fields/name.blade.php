@props([
    'id' => 'nameInput',
    'name' => 'name',
    'value' => '',
    'label' => 'Name',
    'feedback' => 'Please Enter a valid name.',
])


<div class="mb-3">
    <label for='{{ $id }}' class="form-label">{{ $label }} <span class="text-danger">*</span></label>
    <input name='{{ $name }}' type="text" class="form-control text-capitalize" id='{{ $id }}'
        placeholder="Enter name" required oninput="this.value = validateName(this.value, '{{ $id }}');"
        value="{{ $value }}">

    <div class="invalid-feedback">
        {{ $feedback }}
    </div>
    <span class="invalid-feedback">
        @error('firstname')
            {{ $message }}
        @enderror
    </span>
    <span class="invalid-feedback">
        @error('lastname')
            {{ $message }}
        @enderror
    </span>
</div>
