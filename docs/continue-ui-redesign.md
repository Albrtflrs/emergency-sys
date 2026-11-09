# Continue the UI Refresh

Use the current implementation and the direction in `DESIGN.md` and `.impeccable/surfaces/resources-views-public-report-blade-php.md` as the source of truth. The user-approved look is a modern sea-glass civic system: restrained gradients, useful glass surfaces, quiet neumorphic depth, and a single crisp accent for urgent or primary states. Do not revert to the earlier Civic Field Guide-only palette.

The refresh covers the public landing and information pages, news feed and detail, reporting, tracking states, staff login and queue, and admin login, dashboard, queue, and News CMS. Preserve routes, server behavior, roles, privacy boundaries, language support, low-bandwidth usability, keyboard access, and all emergency safety copy. Keep the Kalibo MDRRMO Special Operations Unit identity visible.

The public footer uses only current published CMS items and provides an honest empty state. Image attachments render inline in public notice details; other attachments stay downloadable. Do not fabricate advisories, contact numbers, metrics, or response promises.

Before additional edits, inspect the current worktree and rendered pages. Run `npm run build` and focused tests, then inspect representative public and operational screens in desktop and mobile layouts, including dark mode and error/empty states. Existing `/report` tests may be blocked by the unresolved `PublicReportingController`; report that separately rather than hiding or weakening the route behavior. Update `DESIGN.md` only when the implemented visual system materially changes.