<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tithe extends Model
{
    use HasFactory;
    protected $table = "tithes";
    protected $fillable = ["mfc_user_id", "transaction_id", "payment_mode", "amount", "for_the_month_of", "status"];

    public function user() : BelongsTo {
        return $this->belongsTo(User::class, "mfc_user_id", "mfc_id_number");
    }

    public function transaction() : BelongsTo {
        return $this->belongsTo(Transaction::class, "transaction_id");
    }
}
