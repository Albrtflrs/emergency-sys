<?php

namespace Tests\Unit;

use App\Services\System\FilesystemHealth;
use PHPUnit\Framework\TestCase;

class FilesystemHealthTest extends TestCase
{
    public function test_it_calculates_usage_percent_and_threshold_alerts(): void
    {
        $health = new FilesystemHealth();

        $this->assertSame(80.0, $health->usagePercent(1000, 200));
        $this->assertTrue($health->shouldAlert(80.0, 80.0));
        $this->assertFalse($health->shouldAlert(80.0, 79.9));
    }
}
