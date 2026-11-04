# ADR-0004: Discover public locales from JSON translation files

**Status:** Accepted for implementation; translations awaiting MDRRMO review  
**Date:** 2026-10-16

## Context

Phase 6 starts with Hiligaynon and Kinaray-a completion. SRS FR-I18N-2 requires adding a translation file to register a language and render its available strings without code changes. Public pages must remain server-rendered and must not depend on JavaScript for first paint.

## Decision

Store public UI translations in Laravel JSON files at `lang/{locale}.json`. Each file contains a `__language_name` entry. `LocaleCatalog` discovers valid files and exposes them to a normal POST language switcher. The selected locale is stored in the web session and applied by route middleware. Category lookup and report submission accept only locales present in the catalog.

## Consequences

- Adding a valid JSON file registers a locale without editing application code.
- The language switcher works without JavaScript and does not introduce public third-party scripts.
- JSON is easy to review and version with code.
- Regional translations in this initial change are drafts and require review by MDRRMO language reviewers before launch.
- Invalid and expired tracking links use the same page, status, and message per SRS FR-TRK-3.
