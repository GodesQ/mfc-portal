<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventRegistration extends Model
{
    use HasFactory;
    protected $table = "event_registrations";
    protected $fillable = ["transaction_id", "registration_code", "event_id", "mfc_id_number", "amount", "registered_by", "registered_at"];

    public function user() {
        return $this->hasOne(User::class, "mfc_id_number", "mfc_id_number");
    }

    public function registered_by_user() {
        return $this->belongsTo(User::class,"registered_by");
    }

    public function event() {
        return $this->belongsTo(Event::class, "event_id");
    }

    public function transaction() {
        return $this->belongsTo(Transaction::class, "transaction_id");
    }
}
