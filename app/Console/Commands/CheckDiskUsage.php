<?php

namespace App\Console\Commands;

use App\Services\System\FilesystemHealth;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckDiskUsage extends Command
{
    protected $signature = 'health:check-disk {--threshold=80}';

    protected $description = 'Alert when disk usage crosses the configured threshold.';

    public function handle(FilesystemHealth $health): int
    {
        $path = base_path();
        $threshold = (float) $this->option('threshold');
        $usagePercent = $health->currentUsagePercent($path);

        if ($health->shouldAlert($threshold, $usagePercent)) {
            Log::warning('Disk usage alert triggered', [
                'path' => $path,
                'usage_percent' => $usagePercent,
                'threshold_percent' => $threshold,
            ]);

            $this->warn(sprintf(
                'Disk usage alert: %.2f%% used on %s (threshold: %.0f%%).',
                $usagePercent,
                $path,
                $threshold
            ));

            return self::FAILURE;
        }

        $this->info(sprintf(
            'Disk usage is %.2f%% on %s (threshold: %.0f%%).',
            $usagePercent,
            $path,
            $threshold
        ));

        return self::SUCCESS;
    }
}
