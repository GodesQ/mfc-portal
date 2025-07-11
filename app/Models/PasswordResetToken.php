<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordResetToken extends Model
{
    protected $table = 'password_resets';
    protected $fillable = ['email', 'token', 'created_at'];
    public $timestamps = false;
}
