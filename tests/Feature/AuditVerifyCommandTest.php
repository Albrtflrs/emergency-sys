<?php

namespace Tests\Feature;

use App\Services\Audit\AuditWriter;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AuditVerifyCommandTest extends TestCase
{
    public function test_it_passes_on_a_fresh_chain(): void
    {
        DB::connection('mysql_migrate')->table('audit_logs')->delete();

        app(AuditWriter::class)->record(['action' => 'test.first', 'payload' => []]);

        $this->artisan('audit:verify')->assertExitCode(0);
    }

    public function test_it_fails_on_a_tampered_chain(): void
    {
        DB::connection('mysql_migrate')->table('audit_logs')->delete();

        $writer = app(AuditWriter::class);
        $writer->record(['action' => 'a', 'payload' => ['x' => 1]]);
        $writer->record(['action' => 'b', 'payload' => ['x' => 2]]);

        $tamperedId = DB::connection('mysql_migrate')
            ->table('audit_logs')
            ->latest('id')
            ->value('id');

        DB::connection('mysql_migrate')
            ->table('audit_logs')
            ->where('id', $tamperedId)
            ->update(['row_hash' => hash('sha256', 'tampered-chain')]);

        $this->artisan('audit:verify')->assertExitCode(1);
    }
}