<?php

namespace App\Http\Controllers;

use App\Http\Requests\Ticket\StoreRequest;
use App\Http\Requests\Ticket\UpdateRequest;
use App\Models\Event;
use App\Models\Ticket;
use App\Services\TicketService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EventTicketController extends Controller
{
    public function __construct(private readonly TicketService $ticketService)
    {
    }

    public function index(Event $event): View
    {
        $this->authorizeAccess();

        $tickets = $this->ticketService->listForEvent($event);
        $endPoint = 'Manage Tickets';

        return view('pages.events.tickets.index', compact('event', 'tickets', 'endPoint'));
    }

    public function create(Event $event): View
    {
        $this->authorizeAccess();

        $endPoint = 'Add Ticket';

        return view('pages.events.tickets.create', compact('event', 'endPoint'));
    }

    public function store(StoreRequest $request, Event $event): RedirectResponse
    {
        $this->authorizeAccess();

        try {
            $this->ticketService->create($event, $request->validated());

            return redirect()
                ->route('events.tickets.index', $event)
                ->with('success', 'Ticket created successfully.');
        } catch (Exception $exception) {
            report($exception);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Unable to create the ticket right now.');
        }
    }

    public function edit(Event $event, Ticket $ticket): View
    {
        $this->authorizeAccess();
        $ticket = $this->resolveTicket($event, $ticket);
        $endPoint = 'Edit Ticket';

        return view('pages.events.tickets.edit', compact('event', 'ticket', 'endPoint'));
    }

    public function update(UpdateRequest $request, Event $event, Ticket $ticket): RedirectResponse
    {
        $this->authorizeAccess();
        $ticket = $this->resolveTicket($event, $ticket);

        try {
            $this->ticketService->update($ticket, $request->validated());

            return redirect()
                ->route('events.tickets.index', $event)
                ->with('success', 'Ticket updated successfully.');
        } catch (Exception $exception) {
            report($exception);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Unable to update the ticket right now.');
        }
    }

    public function destroy(Event $event, Ticket $ticket): RedirectResponse
    {
        $this->authorizeAccess();
        $ticket = $this->resolveTicket($event, $ticket);

        try {
            $this->ticketService->delete($ticket);

            return redirect()
                ->route('events.tickets.index', $event)
                ->with('success', 'Ticket deleted successfully.');
        } catch (Exception $exception) {
            report($exception);

            return redirect()
                ->back()
                ->with('error', 'Unable to delete the ticket right now.');
        }
    }

    private function authorizeAccess(): void
    {
        abort_if(!auth()->user()->hasRole('super_admin'), 403);
    }

    private function resolveTicket(Event $event, Ticket $ticket): Ticket
    {
        abort_if((int) $ticket->event_id !== (int) $event->id, 404);

        return $ticket;
    }
}
