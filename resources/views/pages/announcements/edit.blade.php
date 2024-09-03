@extends('layouts.master')
@section('title')
    @lang('translation.announcements') Edit
@endsection
@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            @lang('translation.announcements')
        @endslot
        @slot('title')
            {{ $title }} Edit
        @endslot
    @endcomponent

    <form class="announcements_tablelist_form" autocomplete="off"
        action="{{ route('announcements.update', ['announcement' => $announcement->id]) }}" method="POST">
        @csrf
        @method('PATCH')
        <div class="row">
            <div class="col-8">
                <div class="card">
                    <div class="card-body">
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-lg-12">
                                    <div>
                                        <label for="announcementTitle-field" class="form-label">What?</label>
                                        <input name="title" type="text" id="announcementTitle-field"
                                            class="form-control" placeholder="Title" required
                                            value="{{ old('title', $title) }}" />
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label class="form-label">Content</label>
                                        <textarea name="content" class="ckeditor-classic">{!! $content !!}</textarea>
                                    </div>
                                </div>
                                @if (!$section && !$service)
                                    <div class="col-lg-6 {{ $send_to == 'Everyone' ? 'd-none' : '' }}" id="who_group">
                                        <label for="service" class="form-label">Who?</label>
                                        <select class="form-control" data-plugin="choices" name="service" id="service"
                                            data-choices data-choices-search-false data-choices-removeItem>
                                            <option value="">Service</option>
                                            <option value="1">Area Servants</option>
                                            <option value="2">Chapter Servants</option>
                                            <option value="3">Unit Servants</option>
                                            <option value="4">Household Servants</option>
                                        </select>
                                    </div>

                                    <div class="col-lg-6 {{ $send_to == 'Everyone' ? 'd-none' : '' }}" id="who_group">
                                        <label for="section" class="form-label" style="color: transparent;">x</label>
                                        <select class="form-control" data-plugin="choices" name="section" id="section"
                                            data-choices data-choices-search-false data-choices-removeItem>
                                            <option value="">Section</option>
                                            <option value="1">Kids</option>
                                            <option value="2">Youth</option>
                                            <option value="3">Singles</option>
                                            <option value="4">Handmaids</option>
                                            <option value="5">Servants</option>
                                            <option value="6">Couples</option>
                                        </select>
                                    </div>
                                @endif

                                @if ($section == null && $service)
                                    <div class="col-lg-6 {{ $send_to == 'Everyone' ? 'd-none' : '' }}" id="who_group">
                                        <label for="service" class="form-label">Who?</label>
                                        <select class="form-control" data-plugin="choices" name="service" id="service"
                                            data-choices data-choices-search-false data-choices-removeItem>
                                            <option value="">Service</option>
                                            <option value="1" {{ $service->id == '1' ? 'selected' : '' }}>Area Servants
                                            </option>
                                            <option value="2" {{ $service->id == '2' ? 'selected' : '' }}>Chapter
                                                Servants</option>
                                            <option value="3" {{ $service->id == '3' ? 'selected' : '' }}>Unit Servants
                                            </option>
                                            <option value="4" {{ $service->id == '4' ? 'selected' : '' }}>Household
                                                Servants</option>
                                        </select>
                                    </div>

                                    <div class="col-lg-6 {{ $send_to == 'Everyone' ? 'd-none' : '' }}" id="who_group">
                                        <label for="section" class="form-label" style="color: transparent;">x</label>
                                        <select class="form-control" data-plugin="choices" name="section" id="section"
                                            data-choices data-choices-search-false data-choices-removeItem>
                                            <option value="">Section</option>
                                            <option value="1">Kids</option>
                                            <option value="2">Youth</option>
                                            <option value="3">Singles</option>
                                            <option value="4">Handmaids</option>
                                            <option value="5">Servants</option>
                                            <option value="6">Couples</option>
                                        </select>
                                    </div>
                                @endif

                                @if ($section && $service == null)
                                    <div class="col-lg-6 {{ $send_to == 'Everyone' ? 'd-none' : '' }}" id="who_group">
                                        <label for="service" class="form-label">Who?</label>
                                        <select class="form-control" data-plugin="choices" name="service" id="service"
                                            data-choices data-choices-search-false data-choices-removeItem>
                                            <option value="">Service</option>
                                            <option value="1">Area Servants</option>
                                            <option value="2">Chapter Servants</option>
                                            <option value="3">Unit Servants</option>
                                            <option value="4">Household Servants</option>
                                        </select>
                                    </div>

                                    <div class="col-lg-6 {{ $send_to == 'Everyone' ? 'd-none' : '' }}" id="who_group">
                                        <label for="section" class="form-label" style="color: transparent;">x</label>
                                        <select class="form-control" data-plugin="choices" name="section" id="section"
                                            data-choices data-choices-search-false data-choices-removeItem>
                                            <option value="">Section</option>
                                            <option value="1" {{ $section->id == '1' ? 'selected' : '' }}>Kids
                                            </option>
                                            <option value="2" {{ $section->id == '2' ? 'selected' : '' }}>Youth
                                            </option>
                                            <option value="3" {{ $section->id == '3' ? 'selected' : '' }}>Singles
                                            </option>
                                            <option value="4" {{ $section->id == '4' ? 'selected' : '' }}>Handmaids
                                            </option>
                                            <option value="5" {{ $section->id == '5' ? 'selected' : '' }}>Servants
                                            </option>
                                            <option value="6" {{ $section->id == '6' ? 'selected' : '' }}>Couples
                                            </option>
                                        </select>
                                    </div>
                                @endif

                                @if ($service && $section)
                                    <div class="col-lg-6 {{ $send_to == 'Everyone' ? 'd-none' : '' }}" id="who_group">
                                        <label for="service" class="form-label">Who?</label>
                                        <select class="form-control" data-plugin="choices" name="service" id="service"
                                            data-choices data-choices-search-false data-choices-removeItem>
                                            <option value="">Service</option>
                                            <option value="1" {{ $service->id == '1' ? 'selected' : '' }}>Area
                                                Servants
                                            </option>
                                            <option value="2" {{ $service->id == '2' ? 'selected' : '' }}>Chapter
                                                Servants</option>
                                            <option value="3" {{ $service->id == '3' ? 'selected' : '' }}>Unit
                                                Servants
                                            </option>
                                            <option value="4" {{ $service->id == '4' ? 'selected' : '' }}>Household
                                                Servants</option>
                                        </select>
                                    </div>

                                    <div class="col-lg-6 {{ $send_to == 'Everyone' ? 'd-none' : '' }}" id="who_group">
                                        <label for="section" class="form-label" style="color: transparent;">x</label>
                                        <select class="form-control" data-plugin="choices" name="section" id="section"
                                            data-choices data-choices-search-false data-choices-removeItem>
                                            <option value="">Section</option>
                                            <option value="1" {{ $section->id == '1' ? 'selected' : '' }}>Kids
                                            </option>
                                            <option value="2" {{ $section->id == '2' ? 'selected' : '' }}>Youth
                                            </option>
                                            <option value="3" {{ $section->id == '3' ? 'selected' : '' }}>Singles
                                            </option>
                                            <option value="4" {{ $section->id == '4' ? 'selected' : '' }}>Handmaids
                                            </option>
                                            <option value="5" {{ $section->id == '5' ? 'selected' : '' }}>Servants
                                            </option>
                                            <option value="6" {{ $section->id == '6' ? 'selected' : '' }}>Couples
                                            </option>
                                        </select>
                                    </div>
                                @endif
                            </div>

                            <div class="form-check form-check-success mt-4">
                                <input name="send_to_all" class="form-check-input" type="checkbox" id="send_to_all"
                                    {{ $send_to == 'Everyone' ? 'checked=""' : '' }}>
                                <label class="form-check-label" for="send_to_all">
                                    Send to all?
                                </label>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="col-4">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <h5 class="card-title">
                                @lang('translation.status')
                            </h5>
                            <p class="card-text">
                                <span>Created at: {{ $created_at }}</span>
                            </p>
                            <p class="card-text">
                                <span>Updated at: {{ $updated_at }}</span>
                            </p>
                        </div>

                        <div class="mb-5">
                            <label for="status" class="card-title">
                                @lang('translation.status')
                            </label>
                            <select class="form-control" data-plugin="choices" name="status" id="status"
                                data-choices data-choices-search-false data-choices-removeItem>
                                <option value="">Status</option>
                                <option value="shown" {{ $announcement->status == 'shown' ? 'selected' : '' }}>Shown
                                </option>
                                <option value="hidden" {{ $announcement->status == 'hidden' ? 'selected' : '' }}>Hidden
                                </option>
                            </select>
                        </div>

                        <div class="">
                            <div class="hstack gap-2 justify-content-end">
                                <a href="{{ route('announcements.index') }}" class="btn btn-light">@lang('translation.back')</a>
                                <button type="submit" class="btn btn-success" id=""><i
                                        class="ri-save-line"></i> @lang('translation.update')</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
@section('script')
    <script src="{{ URL::asset('build/js/app.js') }}"></script>

    {{-- @if (session('success'))
        <script></script>
    @endif --}}

    <script>
        $(document).ready(function() {
            var successMessage = @json(session('success'));
            var errorMessage = @json(session('error'));

            if (successMessage) {
                showSuccessMessage(successMessage);
            }

            if (errorMessage) {
                showErrorMessage(errorMessage);
            }


            $('#send_to_all').on('change', function() {
                var group = $('div#who_group');
                if ($(this).is(':checked')) {
                    group.addClass('d-none');
                    $('#service').attr('required', false);
                } else {
                    group.removeClass('d-none');
                    $('#service').attr('required', true);
                }
            });
        })
    </script>
@endsection
