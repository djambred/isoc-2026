<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class TeamMember extends Model
{
    use HasTranslations;

    protected $fillable = [
        'name', 'position', 'photo', 'order', 'is_active',
    ];

    public array $translatable = ['position'];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
