<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;
    protected $table = "transactions";
    protected $fillable = [
        "transaction_code", 
        "reference_code",
        "received_from_id",
        "payer_first_name",
        "payer_last_name",
        "payer_email",
        "payer_contact_number",
        "donation",
        "convenience_fee",
        "sub_amount",
        "early_bird_discount",
        "total_amount",
        "payment_mode",
        "payment_type",
        "checkout_id",
        "payment_link",
        'transaction_response_json',
        "status"
    ];

    public function received_from_user() : BelongsTo {
        return $this->belongsTo(User::class,"received_from_id");
    }

    public function getPayerNameAttribute(): string
    {
        $firstName = $this->received_from_user?->first_name ?? $this->payer_first_name ?? '';
        $lastName = $this->received_from_user?->last_name ?? $this->payer_last_name ?? '';
        $fullName = trim($firstName . ' ' . $lastName);

        return $fullName !== '' ? $fullName : 'Guest User';
    }

    public function getPayerMfcIdNumberAttribute(): ?string
    {
        return $this->received_from_user?->mfc_id_number;
    }
}
