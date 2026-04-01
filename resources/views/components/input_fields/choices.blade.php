@props([
    'label' => '',
    'id' => 'choices',
    'name' => 'choices',
    'formId' => '',
    'isMfcSection' => false,
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

    .choices__item[data-section-slug] {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        background-repeat: no-repeat;
        background-position: left center;
        background-size: 1.75rem 1.75rem;
        padding-left: 2.5rem !important;
        min-height: 1.75rem;
    }

    .choices__item[data-section-slug="kids"] {
        background-image: url("{{ URL::asset('build/images/kids-logo.png') }}");
    }

    .choices__item[data-section-slug="youth"] {
        background-image: url("{{ URL::asset('build/images/youth-logo.png') }}");
    }

    .choices__item[data-section-slug="singles"] {
        background-image: url("{{ URL::asset('build/images/singles-logo.png') }}");
    }

    .choices__item[data-section-slug="handmaids"] {
        background-image: url("{{ URL::asset('build/images/handmaid-logo.png') }}");
    }

    .choices__item[data-section-slug="servants"] {
        background-image: url("{{ URL::asset('build/images/servant-logo.png') }}");
    }

    .choices__item[data-section-slug="couples"] {
        background-image: url("{{ URL::asset('build/images/couples-logo.png') }}");
    }
</style>

<div class="mb-3">
    <label for="{{ $id }}" class="form-label"><span class="text-capitalize">{{ $label }}
        </span><span class="text-danger">*</span></label>
    <select class="form-control" name="{{ $name }}" id="{{ $id }}" required
        @if ($isMfcSection) data-mfc-section-select="true" @endif>
        <option value="">Select {{ $label }}</option>
        {{ $slot }}
    </select>
    <div class="invalid-feedback">Please select atleast one.</div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var selectInput = document.getElementById('{{ $id }}');

        if (!window.initMfcSectionChoices) {
            window.initMfcSectionChoices = function(selectElement, overrides = {}) {
                const sectionSlugByValue = Array.from(selectElement.options).reduce((map, option) => {
                    if (!option.value) {
                        return map;
                    }

                    try {
                        const customProperties = JSON.parse(option.dataset.customProperties || '{}');
                        if (customProperties.sectionSlug) {
                            map[option.value] = customProperties.sectionSlug.toLowerCase();
                        }
                    } catch (error) {
                        // Ignore malformed custom properties and fall back to plain text rendering.
                    }

                    return map;
                }, {});

                const decorateChoices = (container) => {
                    if (!container) {
                        return;
                    }

                    container.querySelectorAll('.choices__item[data-value]').forEach((item) => {
                        const sectionSlug = sectionSlugByValue[item.dataset.value] || '';

                        if (sectionSlug) {
                            item.setAttribute('data-section-slug', sectionSlug);
                        } else {
                            item.removeAttribute('data-section-slug');
                        }
                    });
                };

                const instance = new Choices(selectElement, {
                    searchEnabled: false,
                    shouldSort: false,
                    itemSelectText: '',
                    ...overrides,
                });

                const choicesContainer = selectElement.nextElementSibling &&
                    selectElement.nextElementSibling.classList.contains('choices') ?
                    selectElement.nextElementSibling :
                    null;
                decorateChoices(choicesContainer);

                if (choicesContainer && !choicesContainer.dataset.mfcSectionObserved) {
                    const observer = new MutationObserver(() => decorateChoices(choicesContainer));
                    observer.observe(choicesContainer, {
                        childList: true,
                        subtree: true,
                    });
                    choicesContainer.dataset.mfcSectionObserved = 'true';
                }

                return instance;
            };
        }

        if (selectInput.dataset.mfcSectionSelect === 'true') {
            window.initMfcSectionChoices(selectInput);
        } else {
            new Choices(selectInput, {
                searchEnabled: false,
                shouldSort: false,
            });
        }

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
            const inlineErrorMessage = selectInputs.querySelector('.invalid-feedback');
            const externalErrorMessage = dropdownIcon.nextElementSibling &&
                dropdownIcon.nextElementSibling.classList.contains('danger') ?
                dropdownIcon.nextElementSibling :
                null;

            selectInputs.classList.add('form-control');

            if (selectedValue == '') {
                selectInputs.classList.add('is-invalid');
                selectInputs.classList.remove('is-valid');

                dropdownIcon.classList.add('hide-after');
                dropdownIcon.classList.add('mb-0');

                if (inlineErrorMessage) {
                    inlineErrorMessage.style.display = "block";
                }

                if (externalErrorMessage) {
                    externalErrorMessage.style.display = "block";
                }

                return false;
            } else {
                selectInputs.classList.remove('is-invalid');
                selectInputs.classList.add('is-valid');

                dropdownIcon.classList.add('hide-after');
                dropdownIcon.classList.add('mb-0');

                if (inlineErrorMessage) {
                    inlineErrorMessage.style.display = "none";
                }

                if (externalErrorMessage) {
                    externalErrorMessage.style.display = "none";
                }
                return true;
            }
        }
    });
</script>
