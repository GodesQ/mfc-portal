<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventRegistration extends Model
{
    use HasFactory;
    protected $table = "event_registrations";
    protected $fillable = ["transaction_id", "event_id", "mfc_id_number", "amount", "registered_by", "registered_at"];
}
