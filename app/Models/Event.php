<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'type',
        'section_ids',
        'start_date',
        'end_date',
        'time',
        'location',
        'latitude',
        'longitude',
        'reg_fee',
        'is_early_bird_enabled',
        'early_bird_discount',
        'description',
        'area',
        'options',
        'poster',
        'is_open_for_non_community',
        'is_enable_event_registration',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'section_ids' => 'array',
        'is_open_for_non_community' => 'boolean',
        'is_enable_event_registration' => 'boolean',
        'is_early_bird_enabled' => 'boolean',
        'early_bird_discount' => 'decimal:2',
    ];

    // public function section() {
    //     return $this->belongsTo(Section::class, 'section_id');
    // }

    public function scopeActive(Builder $query) : void
    {
        $query->where('status', 'Active');
    }

    public function sections()
    {
        $sections = Section::select('name')
            ->whereIn('id', $this->section_ids)
            ->get()->map(function ($section) {
                return $section->name;
            })->toArray();
        return $sections;
    }
}
