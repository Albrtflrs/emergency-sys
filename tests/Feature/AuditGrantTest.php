<?php

namespace Tests\Feature;

use App\Services\AuditWriter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class AuditGrantTest extends TestCase
{
    use RefreshDatabase;

    public function test_audit_writer_and_verification_command_work()
    {
        $writer = new AuditWriter();

        $writer->log('report.submitted', 'guest', null, ['reference_code' => 'MDR-2026-000001']);
        
        $exitCode = Artisan::call('audit:verify');

        $this->assertSame(0, $exitCode);
    }
}