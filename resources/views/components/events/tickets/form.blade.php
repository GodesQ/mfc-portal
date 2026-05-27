@props([
    'action',
    'event',
    'method' => 'POST',
    'submitLabel' => 'Save Ticket',
    'ticket' => null,
    'title' => 'Ticket Form',
])

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <form action="{{ $action }}" method="POST">
            @csrf
            @if (!in_array(strtoupper($method), ['GET', 'POST'], true))
                @method($method)
            @endif

            <div class="px-1 pt-1 mb-3">
                <div class="position-relative mb-0 rounded-top overflow-hidden">
                    <img src="{{ URL::asset('build/images/small/img-8.jpg') }}" alt="Ticket cover"
                        class="img-fluid w-100" style="max-height: 220px; object-fit: cover;">
                    <div class="d-flex position-absolute start-0 end-0 top-0 p-4">
                        <div class="flex-grow-1">
                            <h4 class="text-white mb-1">{{ $title }}</h4>
                            <p class="text-white mb-0 opacity-75">Manage ticket details for {{ $event->title ?: 'this event' }}.</p>
                        </div>
                        <div class="flex-shrink-0 d-flex gap-2">
                            <a href="{{ route('events.tickets.index', $event) }}" class="btn btn-light">Back to Tickets</a>
                            <a href="{{ route('events.index') }}" class="btn btn-light">Back to Events</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-4 pt-2">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <div class="alert alert-info">
                    <strong>Event:</strong> {{ $event->title ?: 'Untitled Event' }}
                </div>

                @php
                    $isFree = (bool) old('is_free', $ticket?->is_free ?? false);
                    $isUnlimited = (bool) old('is_unlimited', $ticket?->is_unlimited ?? false);
                @endphp

                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label for="ticket_name" class="form-label">Ticket Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="ticket_name" name="ticket_name"
                                value="{{ old('ticket_name', $ticket?->ticket_name) }}" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3 pt-md-4">
                            <input type="hidden" name="is_free" value="0">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="is_free"
                                    name="is_free" value="1" @checked($isFree)>
                                <label class="form-check-label" for="is_free">Free Ticket</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6" id="price-field">
                        <div class="mb-3">
                            <label for="price" class="form-label">Price <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" class="form-control" id="price" name="price"
                                value="{{ old('price', $ticket?->price) }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3 pt-md-4">
                            <input type="hidden" name="is_unlimited" value="0">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="is_unlimited"
                                    name="is_unlimited" value="1" @checked($isUnlimited)>
                                <label class="form-check-label" for="is_unlimited">Unlimited Tickets</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6" id="ticket-count-field">
                        <div class="mb-3">
                            <label for="total_number_of_tickets" class="form-label">Total Number of Tickets <span class="text-danger">*</span></label>
                            <input type="number" min="1" class="form-control" id="total_number_of_tickets"
                                name="total_number_of_tickets"
                                value="{{ old('total_number_of_tickets', $ticket?->total_number_of_tickets) }}" required>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="4">{{ old('description', $ticket?->description) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('events.tickets.index', $event) }}" class="btn btn-light">Cancel</a>
                    <button type="submit" class="btn btn-primary">{{ $submitLabel }}</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const freeToggle = document.getElementById('is_free');
        const unlimitedToggle = document.getElementById('is_unlimited');
        const price = document.getElementById('price');
        const ticketCount = document.getElementById('total_number_of_tickets');
        const priceField = document.getElementById('price-field');
        const ticketCountField = document.getElementById('ticket-count-field');

        function syncTicketFields() {
            price.disabled = freeToggle.checked;
            price.required = !freeToggle.checked;
            priceField.classList.toggle('opacity-50', freeToggle.checked);

            ticketCount.disabled = unlimitedToggle.checked;
            ticketCount.required = !unlimitedToggle.checked;
            ticketCountField.classList.toggle('opacity-50', unlimitedToggle.checked);
        }

        freeToggle.addEventListener('change', syncTicketFields);
        unlimitedToggle.addEventListener('change', syncTicketFields);
        syncTicketFields();
    });
</script>
