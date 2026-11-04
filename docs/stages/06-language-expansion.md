# Phase 6: Public language expansion

**Status:** Engineering implementation verified; MDRRMO language review pending  
**Selected backlog item:** Hiligaynon and Kinaray-a completion

## Scope

- Discover available language files automatically.
- Let residents switch language on public pages with a server-rendered form.
- Render localized public landing, reporting, and tracking copy, including the hotline and no-dispatch notice.
- Use the requested registered locale for public category names and allow the same registered locales in report payloads.
- Keep invalid, revoked, and expired tracking-link responses indistinguishable.

## Traceability

| Requirement | Implementation | Automated verification |
|---|---|---|
| FR-CHAT-8 | Four locales in the server-rendered selector | `PublicSafetyCopyTest` |
| FR-I18N-2 | `LocaleCatalog` discovers JSON files and renders their strings | `LocaleCatalogTest`, `PublicSafetyCopyTest` |
| FR-TRK-3 | Expired token returns the same 404 view and copy as invalid token | `TrackingTest` |
| COMP-4 | Public pages retain hotline/no-dispatch notice | `PublicSafetyCopyTest` |

## Review and release checks

- Hiligaynon and Kinaray-a strings are draft translations and require MDRRMO language review.
- Complete MySQL-backed suite: **30 tests, 114 assertions passed** on 2026-10-16.
- Vite production asset build and Blade view compilation passed on 2026-10-16.
- Have MDRRMO language reviewers approve the Filipino, Hiligaynon, and Kinaray-a wording before release.
- Verify public category translations against approved names in the live seed/admin data.
- This report does not claim that the MVP or Stage 05 exit gate is complete.
