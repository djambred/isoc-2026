<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;

class SectionItem extends Model
{
    use HasTranslations;

    protected $fillable = [
        'section_id', 'title', 'description', 'icon', 'icon_color',
        'image', 'url', 'extra_data', 'order', 'is_active',
    ];

    public array $translatable = ['title', 'description'];

    protected $casts = [
        'is_active' => 'boolean',
        'extra_data' => 'array',
    ];

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }
}
