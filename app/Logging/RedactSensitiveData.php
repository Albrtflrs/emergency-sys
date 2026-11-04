<?php

namespace App\Logging;

use Monolog\LogRecord;

class RedactSensitiveData
{
    public function __invoke(LogRecord $record): LogRecord
    {
        $message = (string) $record->message;

        $patterns = [
            '/(?<=token=)[^\s&]+/i',
            '/(?<=token_hash=)[^\s&]+/i',
            '/(?<=contact_number=)[^\s&]+/i',
        ];

        foreach ($patterns as $pattern) {
            $message = preg_replace($pattern, '[redacted]', $message) ?? $message;
        }

        $record->message = $message;

        return $record;
    }
}
