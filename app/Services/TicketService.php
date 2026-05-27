<?php

namespace App\Services;

use App\Models\Event;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Collection;

class TicketService
{
    public function listForEvent(Event $event): Collection
    {
        return $event->tickets()->latest()->get();
    }

    public function create(Event $event, array $data): Ticket
    {
        return $event->tickets()->create($this->normalize($data));
    }

    public function update(Ticket $ticket, array $data): Ticket
    {
        $ticket->update($this->normalize($data));

        return $ticket->refresh();
    }

    public function delete(Ticket $ticket): void
    {
        $ticket->delete();
    }

    private function normalize(array $data): array
    {
        $data['is_free'] = (bool) ($data['is_free'] ?? false);
        $data['is_unlimited'] = (bool) ($data['is_unlimited'] ?? false);

        if ($data['is_free']) {
            $data['price'] = 0;
        }

        if ($data['is_unlimited']) {
            $data['total_number_of_tickets'] = null;
        }

        return $data;
    }
}
