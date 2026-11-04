<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Append-only audit log row.
 *
 * This model is intentionally read-mostly. Writes go through
 * App\Services\Audit\AuditWriter, which uses the mysql_audit connection.
 * Attempting to save() an existing model throws, since the app user has no
 * UPDATE grant on the underlying table and we don't want to discover that
 * at runtime.
 */
class AuditLog extends Model
{
    protected $table = 'audit_logs';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $guarded = [];

    protected $casts = [
        'payload'        => 'array',
        'before_summary' => 'array',
        'after_summary'  => 'array',
        'created_at'     => 'datetime',
    ];

    public function getConnectionName(): string
    {
        return 'mysql_audit';
    }

    public function save(array $options = []): bool
    {
        if ($this->exists) {
            throw new \LogicException(
                'AuditLog rows are append-only. Use AuditWriter to record new entries.'
            );
        }

        return parent::save($options);
    }
}