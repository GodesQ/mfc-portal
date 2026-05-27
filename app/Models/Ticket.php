<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'ticket_name',
        'price',
        'description',
        'is_free',
        'is_unlimited',
        'total_number_of_tickets',
    ];

    protected $casts = [
        'event_id' => 'integer',
        'price' => 'decimal:2',
        'is_free' => 'boolean',
        'is_unlimited' => 'boolean',
        'total_number_of_tickets' => 'integer',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}
