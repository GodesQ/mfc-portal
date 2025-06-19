<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = "notifications";

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
    ];

    // Add any custom methods
    public function markAsRead()
    {
        if (is_null($this->read_at)) {
            $this->read_at = now();
            $this->save();
        }
    }

    public function markAsUnread()
    {
        if (!is_null($this->read_at)) {
            $this->read_at = null;
            $this->save();
        }
    }
}
