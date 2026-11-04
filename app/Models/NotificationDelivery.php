<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class NotificationDelivery extends Model
{
    protected $table = 'notification_deliveries';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $guarded = [];

    protected $casts = [
        'attempts' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $delivery): void {
            $delivery->id ??= (string) Str::ulid();
        });
    }

    public function report(): BelongsTo
    {
        return $this->belongsTo(IncidentReport::class, 'report_id');
    }
}
