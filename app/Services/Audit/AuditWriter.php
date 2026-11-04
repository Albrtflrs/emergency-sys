<?php

namespace App\Services\Audit;

use App\Exceptions\AuditChainBrokenException;
use Illuminate\Database\Connection;
use Illuminate\Support\Facades\Context;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AuditWriter
{
    public function __construct(
        private readonly ?Connection $auditDb = null,
    ) {}

    public function log(string $action, ?string $actorRole = null, ?string $targetId = null, array $payload = [], array $context = []): void
    {
        $entry = [
            'action' => $action,
            'actor_role' => $actorRole,
            'target_id' => $targetId,
            'payload' => $payload,
        ];

        foreach (['actor_user_id', 'target_type', 'before_summary', 'after_summary', 'ip'] as $key) {
            if (array_key_exists($key, $context)) {
                $entry[$key] = $context[$key];
            }
        }

        $this->record($entry);
    }

    private function db(): Connection
    {
        return $this->auditDb ?? DB::connection('mysql_audit');
    }

    /**
     * @param array{
     *   action: string,
     *   actor_user_id?: ?int,
     *   actor_role?: ?string,
     *   target_type?: ?string,
     *   target_id?: ?string,
     *   payload?: array,
     *   before_summary?: ?array,
     *   after_summary?: ?array,
     *   ip?: ?string,
     * } $entry
     */
    public function record(array $entry): void
    {
        $this->db()->transaction(function () use ($entry) {
            $last = $this->db()->table('audit_logs')
                ->orderByDesc('id')
                ->lockForUpdate()
                ->first();

            $prevHash = $last->row_hash ?? null;

            $canonicalPayload = $this->canonicalJson($entry['payload'] ?? []);
            $rowHash = hash('sha256', ($prevHash ?? '') . $canonicalPayload);

            $this->db()->table('audit_logs')->insert([
                'actor_user_id'  => $entry['actor_user_id']  ?? null,
                'actor_role'     => $entry['actor_role']     ?? null,
                'action'         => $entry['action'],
                'target_type'    => $entry['target_type']    ?? null,
                'target_id'      => $entry['target_id']      ?? null,
                'payload'        => $canonicalPayload,
                'before_summary' => isset($entry['before_summary'])
                    ? $this->canonicalJson($entry['before_summary']) : null,
                'after_summary'  => isset($entry['after_summary'])
                    ? $this->canonicalJson($entry['after_summary']) : null,
                'ip'             => isset($entry['ip'])
                    ? inet_pton($entry['ip']) : null,
                'correlation_id' => (string) (Context::get('correlation_id') ?? Str::ulid()),
                'prev_hash'      => $prevHash,
                'row_hash'       => $rowHash,
                'created_at'     => now(),
            ]);
        });
    }

    /**
     * @return array{checked:int, last_hash:?string}
     */
    public function verifyChain(): array
    {
        $prev = null;
        $checked = 0;

        $this->db()->table('audit_logs')
            ->orderBy('id')
            ->chunk(500, function ($rows) use (&$prev, &$checked) {
                foreach ($rows as $row) {
                    $expected = hash('sha256', ($prev ?? '') . $row->payload);

                    if (! hash_equals($expected, $row->row_hash)) {
                        throw new AuditChainBrokenException((int) $row->id);
                    }

                    $prev = $row->row_hash;
                    $checked++;
                }
            });

        return ['checked' => $checked, 'last_hash' => $prev];
    }

    private function canonicalJson(array $value): string
    {
        return json_encode(
            $this->ksortRecursive($value),
            JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRESERVE_ZERO_FRACTION
        );
    }

    private function ksortRecursive(array $arr): array
    {
        ksort($arr);
        foreach ($arr as $k => $v) {
            if (is_array($v)) {
                $arr[$k] = $this->ksortRecursive($v);
            }
        }

        return $arr;
    }
}