<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventUserDetail extends Model
{
    use HasFactory;
    protected $table = "event_user_details";
    protected $fillable = ['transaction_id', 'first_name', 'last_name', 'email', 'contact_number', 'address'];
}
