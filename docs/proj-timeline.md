# MDRRMO Emergency Response Reporting Web App — Project Timeline (No Code)

Companion to PRD v1.2 and the SRS. This is the schedule we execute against. Documentation is produced at every stage as part of the work, not after it.

---

## Track

**Solo part-time baseline** — 30 productive hours per week.
**Start:** Monday 2026-10-13.
**Target MVP:** Friday 2027-01-02.
**Target public launch:** Monday 2027-01-12.

If you switch to solo full-time (40 h/week) or add a two-person track, the durations below compress; the sequence does not.

---

## Stage schedule at a glance

| Stage | Name | Weeks | Dates | Exit gate |
|---|---|---:|---|---|
| 00 | Project bootstrap | 1 | 2026-10-13 → 2026-10-17 | Clean clone → migrate → `/up` → CI green |
| 01 | Core reporting | 3 | 2026-10-20 → 2026-11-06 | A real person on a real phone submits a real report and receives a reference code while images still upload |
| 02 | Anonymous tracking | 1 | 2026-11-09 → 2026-11-13 | A token manually expired in the DB reveals nothing on the tracking page |
| 03 | Staff dashboard | 3 | 2026-11-16 → 2026-12-04 | A staff member triages a live report end to end; every action is in the audit chain; chain verifies |
| 04 | Realtime and messaging | 1.5 | 2026-12-07 → 2026-12-16 | Killing Reverb does not break dashboard or tracking; restoring resumes live updates |
| 05 | Hardening and deployment | 2 | 2026-12-17 → 2027-01-02 | Production URL serves the app; backups restore in a drill; staff trained; privacy officer signs off |
| Pilot review | — | 1 | 2027-01-05 → 2027-01-09 | One week of live use with a small staff group, no Sev-1 incident |
| Public launch | — | — | 2027-01-12 | Open to residents |

Total MVP (Stages 00–05): approximately **11.5 calendar weeks**, **310–375 hours**.

---

## Stage 00 — Project bootstrap

**Dates:** 2026-10-13 → 2026-10-17.

**Goal.** A clean Laravel 12 + Vue 3 + Inertia + Tailwind skeleton that boots against MySQL 8, with the two database users in place, correlation IDs flowing through the logs, the audit writer and hash-chain verifier working, and CI running the whole thing.

**Work includes:**

- Repo scaffold and conventions.
- MySQL 8 local, plus `mdrrmo_app` and `mdrrmo_audit` users with the required grants.
- Database connections configured for both users.
- Audit table and settings table migrations.
- Settings seeder with MVP defaults.
- Correlation ID middleware.
- Log redaction processor.
- Audit writer service with hash chain.
- `audit:verify` command.
- Feature tests for chain integrity, tamper detection, and grant denial.
- CI workflow running migrations, tests, and the verifier.
- ADR-0001 (MySQL for MVP), ADR-0002 (ULID primary keys), ADR-0003 (audit grant and hash chain).
- Runbooks: bootstrap local, bootstrap MySQL users, verify audit chain.
- Stage 00 report and CHANGELOG entry.

**Documentation produced:** Stage report, three ADRs, three runbooks, CHANGELOG, README conventions.

**Exit gate.** Clean clone, `composer install`, `npm install`, `php artisan migrate`, `/up` responds, CI green. Tag `stage-00-complete`.

---

## Stage 01 — Core reporting

**Dates:** 2026-10-20 → 2026-11-06 (3 weeks).

**Goal.** A resident can submit a text report with idempotency, categories, optional geolocation, and up to two images processed as WebP with EXIF stripped. Reference code and private tracking link returned. No tracking page yet.

**Week breakdown:**

- **Week 1 (Oct 20–24):** landing page, guided chat skeleton, category migrations and seeders, language switch with English and Filipino strings.
- **Week 2 (Oct 27–31):** submission endpoint with idempotency and reference generation, token hash storage, Turnstile with rate-limit fallback, audit writer wiring into report creation.
- **Week 3 (Nov 3–6):** image pipeline (signature validation, WebP conversion, EXIF stripping, private storage, signed URLs), client uploader with retry and backoff, feature tests, Stage 01 report.

**Documentation produced:** Stage report, ADR-0004 (duplicate detection on MySQL), seeder documentation, updated traceability matrix.

**Exit gate.** Submit a report from a phone on throttled 3G, receive a reference code within seconds, images process in the background. Tag `stage-01-complete`.

---

## Stage 02 — Anonymous tracking

**Dates:** 2026-11-09 → 2026-11-13 (1 week).

**Goal.** A valid tracking link shows status and public timeline for seven days, then stops working. Invalid, revoked, and expired tokens are indistinguishable.

**Work includes:**

- Tracking route with hash validation, per-request expiry check, and strict security headers.
- Tracking page with reference code, submitted time, status timeline, public updates, message thread, expiry countdown.
- Same non-disclosing response for invalid, revoked, and expired tokens.
- Link-saving aids: copy, native share, saved-reports list in local storage.
- Feature tests for every token state.
- Stage 02 report.

**Documentation produced:** Stage report, updated traceability matrix, link-saving aids noted in the operating runbook.

**Exit gate.** Manually expire a token in the database; confirm the page shows only the expiration message and leaks nothing. Tag `stage-02-complete`.

---

## Stage 03 — Staff dashboard

**Dates:** 2026-11-16 → 2026-12-04 (3 weeks).

**Goal.** Staff sign in, see the queue, triage reports, set status and priority, assign, post public updates, add internal notes, mark duplicate or invalid with reason. Email and in-dashboard notifications. Full audit coverage.

**Week breakdown:**

- **Week 1 (Nov 16–20):** users, roles, auth, policies, audit log table finished with full chain, `audit:verify` in daily scheduler.
- **Week 2 (Nov 23–27):** queue with search and filters, report detail with all actions audited, signed-URL evidence views audited, contact-number reveal audited.
- **Week 3 (Nov 30–Dec 4):** duplicate detector on MySQL, notifications (email plus in-dashboard polling), escalation, admin settings, feature tests, Stage 03 report.

**Documentation produced:** Stage report, authorization matrix, notification runbook, updated traceability matrix.

**Exit gate.** A staff member triages a live report end to end; every action appears in the audit chain; `audit:verify` passes. Tag `stage-03-complete`.

---

## Stage 04 — Realtime and messaging

**Dates:** 2026-12-07 → 2026-12-16 (1.5 weeks).

**Goal.** New reports alert staff in realtime, and staff and reporters exchange messages on the private per-report channel. Polling is the mandatory fallback.

**Work includes:**

- Laravel Reverb installation and configuration.
- Staff private channel authorized by session.
- Per-report private channel authorized by tracking token header.
- Two-way messaging with moderation, quick replies, and thread close.
- Minimal broadcast payloads, sensitive data never broadcast.
- Polling fallback exercised by stopping Reverb.
- Optional Telegram or SMS notification for the duty officer.
- End-to-end realtime tests.
- Stage 04 report.

**Documentation produced:** Stage report, realtime runbook, fallback verification evidence, ADR for the notification channel chosen.

**Exit gate.** Kill Reverb — dashboard and tracking continue via polling. Restore Reverb — live updates resume without a page reload. Tag `stage-04-complete`.

---

## Stage 05 — Hardening and deployment

**Dates:** 2026-12-17 → 2027-01-02 (2 weeks, absorbing the Christmas and New Year holidays).

**Goal.** Everything from Stages 00–04 is hardened, deployed, and handed over with training and signed privacy review.

**Work includes:**

- Turnstile and rate-limit tuning under load.
- Upload abuse tests, access-control matrix tests, log redaction tests.
- Alert wiring: stopped workers, repeated notification failures, Reverb down, failed token lookup spike, image processing threshold, audit chain failure, disk above 80 percent.
- Retention jobs, backups, tested restore drill.
- Mobile and low-signal testing on a real device.
- Privacy review sign-off with the privacy officer.
- Staff training deck and operating runbook.
- Production deployment.
- Stage 05 report.

**Documentation produced:** Stage report, deployment runbook, restore-drill evidence, training deck, privacy sign-off, final traceability matrix, updated CHANGELOG.

**Exit gate.** Production URL serves the app, backups restore in a drill, staff trained, privacy officer signs off. Tag `stage-05-complete` and `mvp-v1.0`.

---

## Pilot review

**Dates:** 2027-01-05 → 2027-01-09 (1 week).

**Goal.** One week of live use with a small staff group before opening to residents.

**Work includes:**

- Small-group rollout to duty staff.
- Daily review of queue behavior, notification delivery, and any incident reports from staff.
- Bug fixes only; no new features.
- Collect real-world usage notes for Phase 6.

**Exit gate.** No Sev-1 incident; staff can operate the dashboard unaided; a short pilot report is filed.

---

## Public launch

**Date:** 2027-01-12.

**Work includes:**

- Open reporting to residents.
- Monitor alerts for the first 72 hours.
- Confirm notifications reach the monitored office address.
- Confirm tracking and expiry work under real load.
- Publish the service explanation and hotline reminder on the MDRRMO's existing channels.

---

## Phase 6 — Optional enhancements

No fixed dates. Each item is its own decision and its own small stage.

- First enhancement selected: Hiligaynon and Kinaray-a completion, using the testable language-file registration criteria in SRS FR-I18N-2 and the four-language selector in FR-CHAT-8. Regional copy still requires MDRRMO review before the enhancement can exit.
- Map-based selection refinements.
- OCR and QR detection (assistive only).
- Browser push notifications.
- Analytics beyond operational counts.
- Hiligaynon and Kinaray-a completion.
- Integrations with existing municipal systems.
- Dispatch coordination only if the MDRRMO has an agreed procedure and signs off.

---

## Decision calendar

Which decision is needed by which date, so nothing blocks a stage.

| By end of | Decision | Owner |
|---|---|---|
| 2026-10-17 | MySQL 8.0+ as MVP minimum; MariaDB fallback accepted | Developer |
| 2026-10-17 | ULID internal IDs; separate `mdrrmo_audit` user everywhere | Developer |
| 2026-10-17 | Hosting: VPS (recommended) or shared with polling | Developer + MDRRMO head |
| 2026-10-24 | Official hotline numbers and approved category list | MDRRMO |
| 2026-10-24 | Contact number required (recommended) vs optional | MDRRMO head |
| 2026-10-31 | Dispatch coordination out of MVP scope | MDRRMO head |
| 2026-11-06 | Filipino strings reviewed and approved | MDRRMO |
| 2026-11-13 | Retention periods for reports, evidence, messages, logs | Privacy officer |
| 2026-11-13 | End public access only at seven days (recommended) | Privacy officer |
| 2026-11-20 | Staff alert channel beyond email: Telegram first, SMS if budget | MDRRMO head |
| 2026-12-04 | Two-factor mandatory for administrators | Developer |
| 2026-12-11 | Original-image preservation policy | MDRRMO + legal |
| 2026-12-24 | Privacy review sign-off | Privacy officer |
| 2027-01-02 | Pilot group identified and trained | MDRRMO head |

---

## Effort estimate

| Stage | Hours (part-time) |
|---|---:|
| 00 | 25–30 |
| 01 | 80–95 |
| 02 | 25–30 |
| 03 | 80–95 |
| 04 | 45–55 |
| 05 | 55–70 |
| **MVP total** | **310–375** |

Multiply by your local rate for a budget. If applying for a grant, budget 400 hours to include contingency and the pilot.

---

## Risks

| Risk | Likelihood | Impact | Mitigation | Owner |
|---|---|---|---|---|
| Translation review stalls Stage 01 or 03 | High | Medium | Review strings by Week 4; ship English and Filipino first | MDRRMO |
| Privacy review finds retention problem late | Medium | High | Start the review at Stage 02, not Stage 05 | Privacy officer |
| Imagick or GD with WebP missing on host | Medium | Medium | Verify at Stage 00; GD fallback if Imagick absent | Developer |
| Reverb will not run on chosen host | Medium | Medium | Polling fallback is mandatory; test in Stage 04 | Developer |
| MySQL host is 5.7, not 8.0 | Low | Medium | Haversine fallback in the duplicate detector | Developer |
| Contact-number requirement changes late | Low | Medium | Make it a configurable setting from Stage 01 | MDRRMO head |
| Staff do not adopt the dashboard | Medium | High | Staff UAT in Stage 03; training in Stage 05 | MDRRMO head |
| Key-person risk | High | High | Documentation, ADRs, and runbooks at every stage | Developer |
| Scope creep into dispatch coordination | High | High | PRD explicitly excludes; MDRRMO head signs off | MDRRMO head |

---

## Documentation produced at every stage

For each stage, the following are written as part of the work, not as an afterthought:

- Stage report in `docs/stages/NN-*.md`, following the standard template.
- ADRs in `docs/decisions/` for every decision made during that stage.
- Runbooks in `docs/runbooks/` for any repeatable operation introduced.
- `CHANGELOG.md` entry.
- Updated `docs/traceability.md` mapping requirements to stage, tests, and verification.
- Updated `docs/TIMELINE.md` with actual dates versus planned.

A stage is not complete until its documentation is complete. This is the rule that keeps the project traceable and handover-ready.

---

## Gate definitions (short form)

- **Stage 00:** clean clone → migrate → `/up` → CI green.
- **Stage 01:** a real person on a real phone submits a real report and receives a reference code while images still upload.
- **Stage 02:** a token manually expired in the database reveals nothing on the tracking page.
- **Stage 03:** a staff member triages a live report end to end; every action is in the audit chain; the chain verifies.
- **Stage 04:** killing Reverb does not break dashboard or tracking; restoring resumes live updates.
- **Stage 05:** production URL serves the app; backups restore in a drill; staff trained; privacy officer signs off.
- **MVP:** all of the above, plus a clean one-week pilot with no Sev-1 incident.

---

## What happens next

Say the word and I'll start Stage 00 in the next message, in this order:

1. Ask for your environment details (OS, PHP version, MySQL or Docker).
2. Walk through creating the two MySQL users and grants.
3. Walk through the migrations for the audit log and settings tables.
4. Walk through the audit writer and the `audit:verify` command.
5. Walk through the CI workflow.
6. Fill in the Stage 00 report, ADRs, and runbooks.

Everything goes into the repo as we go, and the timeline above is what we hold ourselves to.
