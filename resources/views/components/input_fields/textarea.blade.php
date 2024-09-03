<div class="mb-3">
    <label class="form-label">{{ $label }}@if (isset($error))
            <span class="text-danger">*</span>
        @endif
    </label>
    <textarea type="text" name="description" class="form-control" rows="5" @if (isset($error)) required @endif>{{ $slot }}</textarea>
    @if (isset($error))
        <div class="invalid-feedback">{{ $error }}</div>
    @endif
</div>
