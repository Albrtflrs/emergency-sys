# MDRRMO — Project Timeline

Track: Solo part-time (30 h/week)
Start: 2026-10-13
Target MVP: 2027-01-02
Target public launch: 2027-01-12

## Stage schedule

| Stage | Name | Weeks | Dates |
|---|---|---|---|
| 00 | Project bootstrap | 1 | 2026-10-13 → 2026-10-17 |
| 01 | Core reporting | 3 | 2026-10-20 → 2026-11-06 |
| 02 | Anonymous tracking | 1 | 2026-11-09 → 2026-11-13 |
| 03 | Staff dashboard | 3 | 2026-11-16 → 2026-12-04 |
| 04 | Realtime and messaging | 1.5 | 2026-12-07 → 2026-12-16 |
| 05 | Hardening and deployment | 2 | 2026-12-17 → 2027-01-02 |
| Pilot review | — | 1 | 2027-01-05 → 2027-01-09 |
| Public launch | — | — | 2027-01-12 |

## Decision calendar

| By end of | Decision | Owner |
|---|---|---|
| 2026-10-17 | MySQL 8.0+ as MVP minimum; MariaDB fallback accepted | Dev |
| 2026-10-17 | ULID internal IDs; separate `mdrrmo_audit` user everywhere | Dev |
| 2026-10-17 | Hosting: VPS (recommended) | Dev + MDRRMO head |
| 2026-10-24 | Official hotline numbers + approved category list | MDRRMO |
| 2026-10-24 | Contact number required (recommended) | MDRRMO head |
| 2026-10-31 | Dispatch coordination out of MVP scope | MDRRMO head |
| 2026-11-06 | Filipino strings reviewed and approved | MDRRMO |
| 2026-11-13 | Retention periods for reports, evidence, messages, logs | Privacy officer |
| 2026-11-13 | End public access only at seven days | Privacy officer |
| 2026-11-20 | Staff alert channel beyond email | MDRRMO head |
| 2026-12-04 | Two-factor mandatory for admins | Dev |
| 2026-12-11 | Original-image preservation policy | MDRRMO + legal |
| 2026-12-24 | Privacy review sign-off | Privacy officer |
| 2027-01-02 | Pilot group identified and trained | MDRRMO head |

## Gates

- Stage 00: clean clone → migrate → `/up` → CI green.
- Stage 01: a real person on a real phone submits a real report and receives a reference code while images still upload.
- Stage 02: a token manually expired in the DB reveals nothing on the tracking page.
- Stage 03: a staff member triages a live report end to end, every action is in the audit chain, chain verifies.
- Stage 04: killing Reverb does not break dashboard or tracking; restoring resumes live updates.
- Stage 05: production URL serves the app, backups restore in a drill, staff trained, privacy officer signs off.

## Risks (open)

| Risk | L | I | Mitigation | Owner |
|---|---|---|---|---|
| Translation review stalls Stage 01/03 | H | M | Review in Week 4; ship en + fil, add others as files | MDRRMO |
| Privacy review finds retention problem late | M | H | Start review at Stage 02 | Privacy officer |
| Imagick/GD with WebP missing on host | M | M | Verify at Stage 00 | Dev |
| Reverb won't run on chosen host | M | M | Polling fallback is mandatory; test in Stage 04 | Dev |
| MySQL host is 5.7, not 8.0 | L | M | Haversine fallback in DuplicateDetector | Dev |
| Contact-number requirement changes late | L | M | Configurable setting from Stage 01 | MDRRMO head |
| Staff don't adopt the dashboard | M | H | Staff UAT in Stage 03; training in Stage 05 | MDRRMO head |
| Key-person risk | H | H | ADRs, runbooks, stage reports | Dev |
| Scope creep into dispatch coordination | H | H | Out of MVP; MDRRMO head signs off | MDRRMO head |

## Effort estimate

| Stage | Hours (part-time) |
|---|---|
| 00 | 25–30 |
| 01 | 80–95 |
| 02 | 25–30 |
| 03 | 80–95 |
| 04 | 45–55 |
| 05 | 55–70 |
| **Total** | **310–375** |