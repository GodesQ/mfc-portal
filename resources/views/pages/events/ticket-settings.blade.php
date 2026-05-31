@extends('layouts.master')
@section('title')
    Ticket Settings
@endsection

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('build/libs/filepond/filepond.min.css') }}" type="text/css" />
    <link rel="stylesheet"
        href="{{ URL::asset('build/libs/filepond-plugin-image-preview/filepond-plugin-image-preview.min.css') }}">
    <style>
        .ticket-asset-preview {
            align-items: center;
            background: #f8f9fa;
            border: 1px solid #e9ebec;
            border-radius: 0.5rem;
            display: flex;
            height: 180px;
            justify-content: center;
            overflow: hidden;
        }

        .ticket-asset-preview img {
            height: 100%;
            max-width: 100%;
            object-fit: contain;
        }

        .ticket-image-preview img {
            object-fit: cover;
            width: 100%;
        }

        #ticket-instructions-editor {
            min-height: 260px;
        }
    </style>
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Events
        @endslot
        @slot('title')
            {{ $endPoint }}
        @endslot
    @endcomponent

    <div class="row mt-3">
        <div class="col-xl-10 mx-auto">
            <div class="card border-0 shadow-sm">
                <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <div>
                        <h5 class="card-title mb-1">Ticket Settings for {{ $event->title ?: 'Untitled Event' }}</h5>
                        <p class="text-muted mb-0">Configure the ticket logo, ticket image, and attendee instructions.</p>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('events.index') }}" class="btn btn-light">
                            <i class="ri-arrow-left-line align-bottom me-1"></i>Back to Events
                        </a>
                        <a href="{{ route('events.tickets.index', $event) }}" class="btn btn-soft-primary">
                            <i class="ri-coupon-3-line align-bottom me-1"></i>Manage Tickets
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form id="ticket-settings-form" action="{{ route('events.ticket_settings.update', $event) }}"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

                        <div class="row g-4">
                            <div class="col-lg-6">
                                <label for="ticket-logo-field" class="form-label">Ticket Logo</label>
                                <div class="ticket-asset-preview mb-3">
                                    @if ($event->ticket_logo)
                                        <img src="{{ URL::asset('uploads/events/' . $event->ticket_logo) }}"
                                            alt="{{ $event->title }} ticket logo">
                                    @else
                                        <span class="text-muted">No logo uploaded</span>
                                    @endif
                                </div>
                                <input type="file" class="filepond" id="ticket-logo-field" name="ticket_logo"
                                    accept="image/*">
                                <small class="text-muted">PNG, JPG, JPEG, or WEBP up to 3MB.</small>
                            </div>

                            <div class="col-lg-6">
                                <label for="ticket-image-field" class="form-label">Ticket Image</label>
                                <div class="ticket-asset-preview ticket-image-preview mb-3">
                                    @if ($event->ticket_image)
                                        <img src="{{ URL::asset('uploads/events/' . $event->ticket_image) }}"
                                            alt="{{ $event->title }} ticket image">
                                    @else
                                        <span class="text-muted">No image uploaded</span>
                                    @endif
                                </div>
                                <input type="file" class="filepond" id="ticket-image-field" name="ticket_image"
                                    accept="image/*">
                                <small class="text-muted">PNG, JPG, JPEG, or WEBP up to 5MB.</small>
                            </div>

                            <div class="col-12">
                                <label for="ticket-instructions-field" class="form-label">Ticket Instructions</label>
                                <textarea name="ticket_instructions" id="ticket-instructions-field" hidden>{{ $event->ticket_instructions }}</textarea>
                                <div id="ticket-instructions-editor"></div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-end align-items-center gap-2">
                            <a href="{{ route('events.index') }}" class="btn btn-light">Cancel</a>
                            <button type="submit" class="btn btn-primary" id="save-ticket-settings-button">
                                Save Ticket Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('ticket-settings-form');
            const submitButton = document.getElementById('save-ticket-settings-button');
            const instructionsField = document.getElementById('ticket-instructions-field');
            const existingInstructions = @json($event->ticket_instructions ?? '');

            const editor = new Quill('#ticket-instructions-editor', {
                theme: 'snow',
                modules: {
                    toolbar: [
                        [{
                            font: []
                        }, {
                            size: []
                        }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{
                            header: [false, 1, 2, 3, 4, 5, 6]
                        }],
                        [{
                            list: 'ordered'
                        }, {
                            list: 'bullet'
                        }],
                        ['direction', {
                            align: []
                        }],
                        ['link', 'image', 'video'],
                        ['clean'],
                    ],
                },
            });

            editor.root.innerHTML = existingInstructions;

            FilePond.registerPlugin(
                FilePondPluginFileEncode,
                FilePondPluginFileValidateSize,
                FilePondPluginImageExifOrientation,
                FilePondPluginImagePreview
            );

            const logoPond = FilePond.create(document.querySelector('#ticket-logo-field'), {
                acceptedFileTypes: ['image/*'],
                allowMultiple: false,
                maxFileSize: '3MB',
                labelIdle: 'Drag & Drop ticket logo or <span class="filepond--label-action">Browse</span>',
            });

            const imagePond = FilePond.create(document.querySelector('#ticket-image-field'), {
                acceptedFileTypes: ['image/*'],
                allowMultiple: false,
                maxFileSize: '5MB',
                labelIdle: 'Drag & Drop ticket image or <span class="filepond--label-action">Browse</span>',
            });

            form.addEventListener('submit', function(event) {
                event.preventDefault();

                syncInstructions();
                removeTicketSettingsErrors();

                const formData = new FormData(form);
                const logoFiles = logoPond.getFiles();
                const imageFiles = imagePond.getFiles();

                if (logoFiles.length > 0) {
                    formData.set('ticket_logo', logoFiles[0].file);
                }

                if (imageFiles.length > 0) {
                    formData.set('ticket_image', imageFiles[0].file);
                }

                submitButton.disabled = true;
                submitButton.textContent = 'Saving...';

                $.ajax({
                    url: form.action,
                    method: 'POST',
                    headers: {
                        Accept: 'application/json',
                    },
                    data: formData,
                    cache: false,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        toastr.success(response.message ?? 'Ticket settings saved.');
                        window.location.reload();
                    },
                    error: function(xhr) {
                        if (xhr.status === 422 && xhr.responseJSON?.errors) {
                            handleTicketSettingsErrors(xhr.responseJSON.errors);
                        }

                        toastr.error(xhr.responseJSON?.message ?? 'Unable to save ticket settings.');
                        submitButton.disabled = false;
                        submitButton.textContent = 'Save Ticket Settings';
                    },
                });
            });

            function syncInstructions() {
                const html = editor.root.innerHTML;
                const text = editor.root.textContent.trim();

                instructionsField.value = text.length ? html : '';
            }

            function removeTicketSettingsErrors() {
                form.querySelectorAll('input, textarea').forEach(function(field) {
                    field.classList.remove('border-danger');
                });
                form.querySelectorAll('.ql-container').forEach(function(container) {
                    container.classList.remove('border', 'border-danger');
                });
                form.querySelectorAll('.filepond--drop-label').forEach(function(label) {
                    label.classList.remove('border-dashed', 'border-danger', 'rounded', 'border-2');
                });
            }

            function handleTicketSettingsErrors(errors) {
                if (errors.ticket_instructions) {
                    form.querySelector('.ql-container')?.classList.add('border', 'border-danger');
                }

                if (errors.ticket_logo) {
                    markFilePondError(logoPond);
                }

                if (errors.ticket_image) {
                    markFilePondError(imagePond);
                }
            }

            function markFilePondError(pond) {
                pond.element
                    ?.querySelector('.filepond--drop-label')
                    ?.classList.add('border-dashed', 'border-danger', 'rounded', 'border-2');
            }
        });
    </script>
@endsection
