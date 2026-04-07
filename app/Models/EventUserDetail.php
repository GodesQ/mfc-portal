<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventUserDetail extends Model
{
    use HasFactory;
    protected $table = "event_user_details";
    protected $fillable = ['event_registration_id', 'user_type', 'first_name', 'last_name', 'email', 'contact_number', 'tshirt_size', 'mfc_section', 'area', 'address'];

    public function event_registration(): BelongsTo
    {
        return $this->belongsTo(EventRegistration::class, 'event_registration_id');
    }
}
