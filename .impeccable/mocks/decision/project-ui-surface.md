# Project UI Redesign

## Scope and visitor mode

Project-wide redesign of resident reporting and tracking, staff login and queue, and admin login, dashboard, and queue. Primary surface: the public report flow. Mode: Operate; the landing page retains its resident-facing explanation and clear actions.

## Audience and task

Residents need to report an incident quickly, even on a low-end phone or unreliable connection, then track the report privately. Staff need to triage and communicate clearly; administrators need to oversee reports and system work. The primary action is to submit useful incident details without implying dispatch.

## Content and constraints

Keep the hotline warning, non-dispatch clarification, existing report and tracking behavior, language support, user roles, privacy boundaries, and real MDRRMO identity. Do not invent response promises or metrics. Preserve semantic controls, keyboard access, responsive behavior, and the low-bandwidth server-rendered experience.

## Direction contract

THESIS: Make this feel like a clear municipal service, not a generic SaaS dashboard or a simulated dispatch console. Keep familiar web navigation and controls; refuse decorative pills, oversized cards, and vague AI-style button treatments.

OWN-WORLD: Civic Field Guide, grounded in Philippine municipal public-service wayfinding and printed civic notices. Use a light, low-glare field, deep teal for the service shell, muted green for supporting surfaces, and amber/red only for meaningful states. Use compact humanist sans typography, crisp sign-like headings, square-edged controls, thin rules, and a restrained numbered step rail. Pair state color with explicit text and strong tonal contrast.

STORY: Residents see the hotline distinction, then a short, ordered reporting task; staff and administrators see their operational work with status, priority, and next actions plainly separated. Tracking communicates recorded progress only, never dispatch.

FIRST VIEWPORT: On the report page, a deep-teal MDRRMO masthead sits above a full-width urgent hotline band. Below, the left rail introduces the task and shows the three numbered steps; the larger right work area contains a compact status label, first-step fields, supporting hint text, and one solid Continue action. At 1440px the form is not trapped in a floating hero card; on mobile it becomes one readable column with the hotline and current step retained.

FORM: Candidate 7 of the grounded direction list; SEED REFERENCE seed:dab939b1ff988447. Transit wayfinding was kept as a competitive alternative for chronological tracking, while the other challenged systems were declined for weak task fit; their useful disciplines are raised in the chosen direction.

FINISH: unreviewed and undocumented is unfinished; this build ends with the finish review, the verdict, DESIGN.md, and every shipping raster carrying its provenance