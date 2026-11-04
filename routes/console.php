<?php

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Artisan;

Artisan::command('audit:verify', function () {
    return $this->call(\App\Console\Commands\VerifyAuditChain::class);
});

$appSchedule = app(Schedule::class);
$appSchedule->command('audit:verify')->dailyAt('01:00');
$appSchedule->command('health:check-disk --threshold=80')->dailyAt('02:15');