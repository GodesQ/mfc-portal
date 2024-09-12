<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'user_id',
        'service_id',
        'section_id',
        'status',
        'created_at',
        'updated_at',
    ];

    public function images() {
        return $this->hasMany(AnnouncementImage::class, 'announcement_id');
    }
}
