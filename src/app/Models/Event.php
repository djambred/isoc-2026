<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Event extends Model
{
    use HasTranslations;

    protected $fillable = [
        'title', 'description', 'category', 'image', 'date',
        'time_info', 'location', 'location_type', 'registration_url',
        'capacity_info', 'max_participants', 'registration_open',
        'attendance_code',
        'is_featured', 'is_active', 'order', 'certificate_template',
    ];

    public array $translatable = ['title', 'description', 'category', 'time_info', 'location', 'capacity_info'];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'registration_open' => 'boolean',
        'date' => 'date',
    ];

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('date', '>=', now());
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function confirmedRegistrations(): HasMany
    {
        return $this->hasMany(EventRegistration::class)->where('status', '!=', 'cancelled');
    }

    public function isFull(): bool
    {
        if (!$this->max_participants) {
            return false;
        }

        return $this->confirmedRegistrations()->count() >= $this->max_participants;
    }

    public function canRegister(): bool
    {
        return $this->registration_open && $this->is_active && !$this->isFull();
    }
}
