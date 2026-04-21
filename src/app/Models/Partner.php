<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Partner extends Model
{
    use HasTranslations;

    protected $fillable = [
        'name', 'description', 'subtitle', 'logo', 'logo_url',
        'url', 'type', 'order', 'is_active',
    ];

    public array $translatable = ['name', 'description', 'subtitle'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeInternational($query)
    {
        return $query->where('type', 'international');
    }

    public function scopeNational($query)
    {
        return $query->where('type', 'national');
    }
}
