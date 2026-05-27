@extends('layouts.master')
@section('title')
    Add Ticket
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
            <x-events.tickets.form :action="route('events.tickets.store', $event)" :event="$event" method="POST"
                submit-label="Save Ticket" :ticket="null" title="Add Ticket" />
        </div>
    </div>
@endsection
