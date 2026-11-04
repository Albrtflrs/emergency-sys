<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class IncidentCategory extends Model
{
    protected $table = 'incident_categories';

    protected $guarded = [];

    protected $casts = [
        'name_translations' => 'array',
        'active'            => 'boolean',
        'sort_order'        => 'integer',
    ];

    public function reports(): HasMany
    {
        return $this->hasMany(IncidentReport::class, 'category_id');
    }

    public function nameFor(string $locale, ?string $fallback = 'en'): string
    {
        $names = $this->name_translations ?? [];

        return $names[$locale]
            ?? $names[$fallback]
            ?? $this->slug;
    }

    public function scopeActive($query)
    {
        return $query->where('active', true)->orderBy('sort_order')->orderBy('id');
    }
}