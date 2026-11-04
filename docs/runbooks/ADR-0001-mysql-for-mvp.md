# ADR-0001: MySQL 8.0+ as the MVP database

## Status
Accepted, 2026-10-13.

## Context
The PRD targets a small municipal deployment: 100 to 500 reports per month,
one 2 GiB VPS, a single Laravel monolith. The database must support:

- Standard relational data (reports, statuses, users, messages, audit).
- Geospatial proximity queries for duplicate detection (200 m radius).
- Full-text search over descriptions and location text.
- Append-only audit log with grant-based write protection.

The candidate engines were PostgreSQL 16 (with PostGIS for spatial) and
MySQL 8.0.

## Decision
Use MySQL 8.0+ with InnoDB and utf8mb4 for the MVP. MariaDB 10.5+ is
acceptable as a fallback if MySQL 8 is unavailable on the target host,
with a Haversine fallback for duplicate detection.

## Consequences
- `ST_Distance_Sphere` covers the duplicate detection requirement without
  PostGIS. The bounding-box + Haversine path remains available for MariaDB.
- MySQL has no row-level security, so audit-table immutability is enforced
  by database grants plus a SHA-256 hash chain. This is the design chosen
  in ADR-0003.
- Full-text search uses MySQL's InnoDB FULLTEXT. It is behind a repository
  interface so a future Postgres migration can swap to `tsvector` without
  touching callers.
- The application uses ULID primary keys (ADR-0002) because MySQL's UUID()
  is version 1 and index-unfriendly.
- A future migration to Postgres with PostGIS is possible without rewriting
  business logic, provided spatial and full-text behavior stays inside
  repositories. This is tracked as a portability requirement.

## Alternatives considered
- PostgreSQL 16 + PostGIS: better spatial and full-text primitives, and
  row-level security. Rejected for MVP because the likely production host
  runs MySQL, and the spatial and full-text requirements are modest.
- MariaDB 10.5+: acceptable fallback, but the project pins MySQL 8.0+ as the
  minimum so ST_Distance_Sphere and JSON behavior are consistent.