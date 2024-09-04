<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model
{
    use HasFactory;
    protected $table = "user_details";

    protected $fillable = [
        "user_id",
        "god_given_skill",
        "address",
        "birthday",
        "facebook_link",
        "instagram_link",
        "twitter_link"
    ];

    protected $casts = [
        "god_given_skill" => "array",
    ];
}
