<?php

namespace App\Console\Commands;

use App\Exceptions\AuditChainBrokenException;
use App\Services\Audit\AuditWriter;
use Illuminate\Console\Command;

class VerifyAuditChain extends Command
{
    protected $signature = 'audit:verify';

    protected $description = 'Verify the audit_logs hash chain.';

    public function handle(AuditWriter $writer): int
    {
        try {
            $result = $writer->verifyChain();
        } catch (AuditChainBrokenException $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }

        $this->info(sprintf(
            'Audit chain OK. Checked %d rows. Last hash: %s',
            $result['checked'],
            $result['last_hash'] ?? '(empty)'
        ));

        return self::SUCCESS;
    }
}
