<?php
namespace Tests\Feature;

use App\Services\AuditWriter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AuditChainTest extends TestCase
{
use RefreshDatabase;

protected function defineEnvironment($app)
{
// Use mysql_audit connection for testing
$app['config']->set('database.default', 'mysql');
}

public function test_audit_chain_verifies_successfully()
{
$writer = new AuditWriter();

// Write sample audit entries
$writer->log('report.submitted', 'guest', null, ['reference_code' => 'MDR-2026-000001']);
$writer->log('report.viewed', 'staff', 'user_123', ['report_id' => '01H...']);

// Run the verification command
$exitCode = Artisan::call('audit:verify');

$this->assertSame(0, $exitCode);
}
}