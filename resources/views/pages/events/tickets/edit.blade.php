@extends('layouts.master')
@section('title')
    Edit Ticket
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
            <x-events.tickets.form :action="route('events.tickets.update', ['event' => $event, 'ticket' => $ticket])"
                :event="$event" method="PUT" submit-label="Update Ticket" :ticket="$ticket" title="Edit Ticket" />
        </div>
    </div>
@endsection
