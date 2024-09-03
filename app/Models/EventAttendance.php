<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventAttendance extends Model
{
    use HasFactory;
    protected $table = "event_attendances";
    protected $fillable = ["event_id", "user_id", "attendance_date"];

    protected $casts = [
        "attendance_date" => "date",
    ];

    public function event() : BelongsTo {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function user() : BelongsTo {
        return $this->belongsTo(User::class, 'user_id');
    }
}
