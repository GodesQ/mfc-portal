@extends('layouts.master')
@section('title')
    Manage Tickets
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
        <div class="col-lg-12">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="card border-0 shadow-sm">
                <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <div>
                        <h5 class="card-title mb-1">Tickets for {{ $event->title ?: 'Untitled Event' }}</h5>
                        <p class="text-muted mb-0">View, add, edit, and delete tickets assigned to this event.</p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('events.index') }}" class="btn btn-light">Back to Events</a>
                        <a href="{{ route('events.tickets.create', $event) }}" class="btn btn-primary">
                            <i class="ri-add-line align-bottom me-1"></i>Add Ticket
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle table-striped mb-0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Ticket Name</th>
                                    <th>Price</th>
                                    <th>Availability</th>
                                    <th>Created</th>
                                    <th>Updated</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($tickets as $ticket)
                                    <tr>
                                        <td>{{ $ticket->id }}</td>
                                        <td>{{ $ticket->ticket_name ?: 'N/A' }}</td>
                                        <td>
                                            @if ($ticket->is_free)
                                                <span class="badge bg-success-subtle text-success">Free</span>
                                            @else
                                                {{ number_format((float) $ticket->price, 2) }}
                                            @endif
                                        </td>
                                        <td>
                                            @if ($ticket->is_unlimited)
                                                Unlimited
                                            @else
                                                {{ $ticket->total_number_of_tickets ?? 'N/A' }}
                                            @endif
                                        </td>
                                        <td>{{ optional($ticket->created_at)->format('F d, Y h:i A') ?: 'N/A' }}</td>
                                        <td>{{ optional($ticket->updated_at)->format('F d, Y h:i A') ?: 'N/A' }}</td>
                                        <td class="text-end">
                                            <div class="d-inline-flex gap-2">
                                                <a href="{{ route('events.tickets.edit', ['event' => $event, 'ticket' => $ticket]) }}"
                                                    class="btn btn-soft-success btn-sm">Edit</a>
                                                <form action="{{ route('events.tickets.destroy', ['event' => $event, 'ticket' => $ticket]) }}"
                                                    method="POST" onsubmit="return confirm('Delete this ticket permanently?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-soft-danger btn-sm">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5">
                                            <h6 class="mb-1">No tickets yet</h6>
                                            <p class="text-muted mb-3">Create the first ticket for this event to start managing inventory and pricing.</p>
                                            <a href="{{ route('events.tickets.create', $event) }}" class="btn btn-primary btn-sm">Add Ticket</a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
