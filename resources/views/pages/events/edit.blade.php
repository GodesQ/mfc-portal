@extends('layouts.master')
@section('title')
    Edit Event
@endsection

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('build/libs/filepond/filepond.min.css') }}" type="text/css" />
    <link rel="stylesheet"
        href="{{ URL::asset('build/libs/filepond-plugin-image-preview/filepond-plugin-image-preview.min.css') }}">
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Events
        @endslot
        @slot('title')
            {{ $endPoint ?? 'Edit Event' }}
        @endslot
    @endcomponent

    <div class="row mt-3">
        <div class="col-xl-10 mx-auto">
            @component('components.events.edit-form', ['event' => $event, 'sections' => $sections])
            @endcomponent
        </div>
    </div>
@endsection
