@props([
    'id' => 'contactNumberInput',
    'name' => 'contact_number',
    'value' => '',
    'formId' => 'userEditForm'
])


<div class="mb-3">
    <label for='{{ $id }}' class="form-label">Contact Number <span class="text-danger">*</span></label>
    <div class="form-icon">
        <input name={{ $name }} type="text" class="form-control form-control-icon" id='{{ $id }}'
            required oninput="validateContactNumber(this);" pattern="^9\d{2} \d{3} \d{4}$" value="{{ $value }}" placeholder="912 345 6789" style="padding-left: 55px;">
        <i style="left: 15px;" class="fst-normal"><span class="position-absolute" style="top: 9px;">(+63)</span></i>
        <div class="invalid-feedback">Please Enter a valid contact number.</div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        if (document.getElementById('{{ $id }}')) {
            var cleavePrefix = new Cleave('#{{ $id }}', {
                delimiter: ' ',
                blocks: [3, 3, 4],
            });
        }

        const form = document.getElementById('{{ $formId }}');
        const inputElement = document.getElementById('{{ $id }}');

        form.addEventListener('submit', function(event) {
            validateContactNumber(inputElement);
        });


    });
</script>
