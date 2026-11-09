<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NewsPost extends Model
{
    public const CATEGORIES = [
        'weather_advisory' => 'Weather Advisory',
        'suspension_notice' => 'Suspension Notice',
        'relief_operations' => 'Relief Operations',
        'preparedness_guide' => 'Preparedness Guide',
        'press_release' => 'Press Release',
    ];

    public const SEVERITIES = ['normal', 'urgent', 'critical'];

    public const STATUSES = ['draft', 'published', 'scheduled', 'archived'];

    protected $fillable = [
        'user_id', 'title', 'slug', 'excerpt', 'body', 'category', 'severity',
        'status', 'is_banner', 'attachments', 'published_at', 'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'is_banner' => 'boolean',
            'attachments' => 'array',
            'published_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeVisibleToPublic(Builder $query): Builder
    {
        return $query
            ->whereIn('status', ['published', 'scheduled'])
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->where(function (Builder $query): void {
                $query->whereNull('expires_at')->orWhere('expires_at', '>', now());
            });
    }
}