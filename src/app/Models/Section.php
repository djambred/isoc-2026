<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Section extends Model
{
    use HasTranslations;

    protected $fillable = [
        'page', 'key', 'title', 'subtitle', 'description',
        'image', 'button_text', 'button_url',
        'secondary_button_text', 'secondary_button_url',
        'order', 'is_active',
    ];

    public array $translatable = ['title', 'subtitle', 'description', 'button_text', 'secondary_button_text'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(SectionItem::class)->orderBy('order');
    }

    public function activeItems(): HasMany
    {
        return $this->hasMany(SectionItem::class)->where('is_active', true)->orderBy('order');
    }

    public static function pageCandidates(string $page): array
    {
        $page = strtolower(trim($page));

        return match ($page) {
            'event', 'events' => ['events', 'event'],
            'mitra', 'ourpartner', 'partner' => ['mitra', 'ourpartner'],
            default => [$page],
        };
    }

    public static function getByPageAndKey(string $page, string $key): ?self
    {
        return static::whereIn('page', static::pageCandidates($page))
            ->where('key', $key)
            ->where('is_active', true)
            ->orderBy('order')
            ->first();
    }

    public static function getPageSections(string $page): \Illuminate\Database\Eloquent\Collection
    {
        return static::whereIn('page', static::pageCandidates($page))
            ->where('is_active', true)
            ->orderBy('order')
            ->get()
            ->keyBy('key');
    }
}
