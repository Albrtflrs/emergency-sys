<?php

namespace App\Services\System;

class FilesystemHealth
{
    public function usagePercent(float|int $totalBytes, float|int $freeBytes): float
    {
        if ($totalBytes <= 0) {
            return 0.0;
        }

        $usedBytes = max(0, $totalBytes - $freeBytes);

        return round(($usedBytes / $totalBytes) * 100, 2);
    }

    public function currentUsagePercent(string $path = '/'): float
    {
        $totalBytes = disk_total_space($path);
        $freeBytes = disk_free_space($path);

        if ($totalBytes === false || $freeBytes === false || $totalBytes <= 0) {
            return 0.0;
        }

        return $this->usagePercent($totalBytes, $freeBytes);
    }

    public function shouldAlert(float $thresholdPercent, float $usagePercent): bool
    {
        return $usagePercent >= $thresholdPercent;
    }
}
