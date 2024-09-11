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
        "donation",
        "convenience_fee",
        "sub_amount", 
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
}
