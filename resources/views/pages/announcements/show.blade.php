@extends('layouts.master')
@section('title')
    @lang('translation.starter')
@endsection
@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            <a href="{{ route('announcements.index') }}">@lang('translation.announcements')</a>
        @endslot
        @slot('title')
            {{ $title }}
        @endslot
    @endcomponent

    <div class="flex"></div>

    <div class="row">
        <div class="col-8">
            <div class="card">
                <div class="card-body">
                    <div class=" mb-5">
                        <h6 class="mb-3 fw-semibold text-uppercase">{{ $title }}</h6>
                        {!! $content !!}
                    </div>

                    <div class="text-muted">
                        <span>Send to:</span>
                        <div class="row">
                            <span class="col">{{ $send_to }}</span>
                        </div>
                    </div>

                    <div class="mt-5 card-footer">
                        <div class="d-flex justify-content-center gap-2">
                            <a href="{{ route('announcements.edit', ['announcement' => $announcement->id]) }}"
                                class="btn btn-success"><i class="ri-pencil-line"></i> @lang('translation.edit')</a>
                            <button type="button" class="btn btn-danger del-btn" id="{{ $announcement->id }}"><i
                                    class="ri-delete-bin-line"></i>
                                @lang('translation.delete')</button>
                        </div>
                    </div>
                </div>
                <!-- end card body -->
            </div>
        </div>
        <div class="col-4">
            <div class="card">
                <div class="card-body">
                    <div class="row text-muted">
                        <span>Created at: {{ $created_at }}</span>
                        <span>Last Updated at: {{ $updated_at }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ URL::asset('build/js/app.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('.del-btn').click(function() {
                var id = $(this).attr('id');
                showDeleteMessage({
                    message: 'Are you sure you want to Delete this Announcement?',
                    deleteFunction: function() {
                        // some function
                        $.ajax({
                            url: '/announcement/' + id,
                            type: 'DELETE',
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                showSuccessMessage(response.message);
                            },
                            error: function(xhr, response) {
                                showErrorMessage(response.message);
                            }
                        });
                    }
                })
            })
        });
    </script>
@endsection
