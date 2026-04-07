<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class EventRegistration extends Model
{
    use HasFactory;
    protected $table = "event_registrations";
    protected $fillable = ["transaction_id", "registration_code", "event_id", "user_id", "mfc_id_number", "amount", "registered_by", "registered_at"];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, "user_id");
    }

    public function primary_user(): HasOne
    {
        return $this->hasOne(EventUserDetail::class, "event_registration_id")->where('user_type', 'primary')->withDefault();
    }

    public function registered_by_user(): BelongsTo
    {
        return $this->belongsTo(User::class, "registered_by");
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class, "event_id");
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class, "transaction_id");
    }

    public function event_user_detail(): HasOne
    {
        return $this->hasOne(EventUserDetail::class, 'event_registration_id');
    }

    public function getDisplayNameAttribute(): string
    {
        $firstName = $this->user?->first_name ?? $this->event_user_detail?->first_name ?? '';
        $lastName = $this->user?->last_name ?? $this->event_user_detail?->last_name ?? '';
        $fullName = trim($firstName . ' ' . $lastName);

        return $fullName !== '' ? $fullName : 'Guest User';
    }

    public function getDisplayMfcIdNumberAttribute(): string
    {
        return $this->user?->mfc_id_number ?? $this->mfc_id_number ?? 'Guest User';
    }

    public function getDisplayEmailAttribute(): string
    {
        return $this->user?->email
            ?? $this->event_user_detail?->email
            ?? 'Not Found';
    }

    public function getDisplayContactNumberAttribute(): string
    {
        return $this->user?->contact_number
            ?? $this->event_user_detail?->contact_number
            ?? 'Not Found';
    }

    public function getDisplayAddressAttribute(): string
    {
        return $this->user?->user_details?->address
            ?? $this->event_user_detail?->address
            ?? 'Not Found';
    }
}
