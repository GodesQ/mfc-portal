@props([
    'label' => '',
    'id' => 'choices',
    'name' => 'choices',
    'formId' => '',
])

<style>
    .hide-after::after {
        content: "";
        display: none;
        margin-bottom: 0px;
    }

    .choices__item.choices__item--selectable {
        text-transform: capitalize;
    }

    .form-control.is-valid {
        border-color: var(--vz-form-valid-border-color) !important;
    }
</style>

<div class="mb-3">
    <label for="{{ $id }}" class="form-label"><span class="text-capitalize">{{ $label }}
        </span><span class="text-danger">*</span></label>
    <select class="form-control" name="{{ $name }}" id="{{ $id }}" required>
        <option value="">Select {{ $label }}</option>
        {{ $slot }}
    </select>
    <div class="invalid-feedback">Please select atleast one.</div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var selectInput = document.getElementById('{{ $id }}');

        new Choices(selectInput, {
            searchEnabled: false,
            shouldSort: false,
        });

        // Add a custom event listener for validation on change
        document.getElementById('{{ $id }}').addEventListener('change', function() {
            validateSelect(this);
        });

        document.getElementById('{{ $formId }}').addEventListener('submit', function(event) {
            validateForm();
        });

        function validateForm() {
            const selectElement = document.getElementById('{{ $id }}');
            if (validateSelect(selectElement)) {
                // Form is valid
                return true;
            } else {
                // Form is invalid
                return false;
            }
        }

        function validateSelect(selectElement) {
            const selectedValue = selectElement.value;
            const selectInputs = selectElement.parentElement;
            const dropdownIcon = selectInputs.parentElement;
            const errorMessage = dropdownIcon.nextElementSibling;

            selectInputs.classList.add('form-control');

            if (selectedValue == '') {
                selectInputs.classList.add('is-invalid');
                selectInputs.classList.remove('is-valid');

                dropdownIcon.classList.add('hide-after');
                dropdownIcon.classList.add('mb-0');

                errorMessage.style.display = "block";

                return false;
            } else {
                selectInputs.classList.remove('is-invalid');
                selectInputs.classList.add('is-valid');

                dropdownIcon.classList.add('hide-after');
                dropdownIcon.classList.add('mb-0');

                errorMessage.style.display = "none";
                return true;
            }
        }
    });
</script>
