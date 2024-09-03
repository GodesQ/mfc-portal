<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserMissionaryService extends Model
{
    use HasFactory;
    protected $table = "user_missionary_services";
    protected $fillable = [
        "user_id","service_category","service_type","section","area"
    ];
}
