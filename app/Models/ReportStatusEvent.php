<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class ReportStatusEvent extends Model
{
    protected $table = 'report_status_events';

    protected $keyType = 'string';

    public $incrementing = false;

    public $timestamps = false;

    protected $guarded = [];

    protected $casts = ['created_at' => 'datetime'];

    protected static function booted(): void
    {
        static::creating(function (self $event): void {
            $event->id ??= (string) Str::ulid();
            $event->created_at ??= now();
        });
    }

    public function report(): BelongsTo
    {
        return $this->belongsTo(IncidentReport::class, 'report_id');
    }
}
