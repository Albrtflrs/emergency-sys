# ADR-0003: Audit immutability via grants and a hash chain

## Status
Accepted, 2026-10-13.

## Context
The PRD requires an audit log that cannot be modified or deleted through the
application, with tamper detection for privileged actors. On PostgreSQL this
would be enforced with row-level security. MySQL has no equivalent, so the
guarantee must come from a different mechanism.

## Decision
Enforce audit immutability with two layers:

1. **Database grants.** The application user `mdrrmo_app` has only SELECT and
   INSERT on `audit_logs`. It has no UPDATE and no DELETE. A separate user
   `mdrrmo_audit` has SELECT and INSERT on `audit_logs` and no access to any
   other table. The migration user `mdrrmo_migrate` has DDL but is never
   used at runtime.

2. **Hash chain.** Each audit row stores `prev_hash` and `row_hash`, where
   `row_hash = SHA-256(prev_hash || canonical_payload)`. The genesis row has
   a NULL `prev_hash`. Writes lock the last row (`SELECT ... FOR UPDATE`)
   to serialize the chain.

A scheduled command `php artisan audit:verify` walks the chain in id order
and fails on the first mismatch, raising an administrator alert.

## Consequences
- Application code cannot modify or delete audit rows even if it tries.
- A DBA with root access can still tamper with rows, but doing so breaks the
  chain and is detectable by the verification job on its next run.
- `payload` is stored as a canonical JSON string so the hash is deterministic.
  If MySQL's JSON normalization ever causes drift, we add a `payload_canonical
  LONGTEXT` column and hash that instead. This is a known risk tracked in the
  Stage 00 report.
- The verification job must run at least daily. If it fails, the alert fires.

## Alternatives considered
- PostgreSQL row-level security: not available on the MVP database (see
  ADR-0001). Kept as the target mechanism for a future Postgres migration.
- Application-level "insert-only" convention: rejected as unenforceable. A
  convention is not a guarantee.
- Write-once object storage for audit rows: rejected as over-engineered for
  the MVP and it complicates queries and exports.

## Open risks
- MySQL's JSON column normalization could reorder keys. The canonical JSON
  encoding in AuditWriter sorts keys recursively to make this deterministic.
  A test in Stage 00 verifies that a value written and read back hashes to
  the same string.