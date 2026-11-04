<?php

namespace App\Exceptions;

use RuntimeException;

class AuditChainBrokenException extends RuntimeException
{
    public function __construct(
        public readonly int $auditRowId,
        string $message = '',
    ) {
        parent::__construct($message ?: "Audit chain broken at row {$auditRowId}");
    }
}