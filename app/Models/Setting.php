<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * Key-value settings, backed by the settings table.
 *
 * Values are stored as JSON and returned decoded. Reads are cached for the
 * duration of a request; the cache key is versioned so writes invalidate.
 */
class Setting extends Model
{
    protected $table = 'settings';

    protected $primaryKey = 'key';

    protected $keyType = 'string';

    public $incrementing = false;

    public $timestamps = false;

    protected $guarded = [];

    protected $casts = [
        'value'      => 'array',
        'updated_at' => 'datetime',
    ];

    public static function get(string $key, mixed $default = null): mixed
    {
        $row = Cache::remember(
            'settings.' . $key,
            now()->addMinutes(5),
            fn () => static::query()->find($key)
        );

        return $row?->value ?? $default;
    }

    public static function put(string $key, mixed $value): void
    {
        static::query()->updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'updated_at' => now()]
        );

        Cache::forget('settings.' . $key);
    }

    public static function forget(string $key): void
    {
        Cache::forget('settings.' . $key);
    }
}