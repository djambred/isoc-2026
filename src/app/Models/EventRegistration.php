<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class EventRegistration extends Model
{
    protected $fillable = [
        'event_id', 'name', 'email', 'phone',
        'organization', 'position', 'motivation',
        'status', 'registration_code', 'password',
        'attended_at', 'certificate_path', 'is_speaker',
    ];

    protected $hidden = ['password'];

    protected function casts(): array
    {
        return [
            'attended_at' => 'datetime',
            'is_speaker' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $registration) {
            if (empty($registration->registration_code)) {
                $registration->registration_code = 'REG-' . strtoupper(Str::random(8));
            }
        });
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}
