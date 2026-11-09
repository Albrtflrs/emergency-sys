---
version: 1
slug: "resources-views-public-report-blade-php"
primary_target: "resources/views/public/report.blade.php"
related_targets: ["resources/views/landing.blade.php","resources/views/public/navigation.blade.php","resources/views/news/index.blade.php","resources/views/news/show.blade.php","resources/views/public/about.blade.php","resources/views/public/how-it-works.blade.php","resources/views/tracking/show.blade.php","resources/views/staff/dashboard.blade.php","resources/views/admin/dashboard.blade.php","resources/views/admin/queue.blade.php"]
---

## Project-wide Refresh

### Scope and visitor mode
Project-wide visual refresh across public landing, informational, news, report, and tracking pages plus staff/admin login and work screens. Modes: Read for public updates, Operate for forms and consoles, Persuade only for the public home introduction.

### Audience and task
Residents need a clear route to verified updates, emergency reporting, and private tracking. MDRRMO officers need readable, low-friction triage and publishing controls. Keep every current route, action, role boundary, and data contract working.

### Content and constraints
The footer's recent-news area uses only active published CMS posts, ordered newest first, with a truthful empty state. Use the actual Kalibo logo, MDRRMO/Special Operations Unit identity, and existing municipal imagery. Never invent advisories, official contact numbers, response commitments, or dispatch behavior. Preserve hotline warning, accessible controls, localization, and mobile/low-bandwidth support.

### Direction contract
THESIS: Recast the portal as a contemporary civic service, not an anonymous Laravel admin skin. Residents can move from urgent facts to useful action without hunting; operators retain familiar, fast controls.

OWN-WORLD: A sea-glass civic interface: deep teal and cobalt gradients anchor the shell; cool white, mist, and ink surfaces carry the work; amber signals urgency and coral marks critical states only. Use restrained translucent glass at navigation and summary surfaces, tactile soft depth for ordinary work panels, and one crisp dark-edged action or alert at a time for Neo-Brutalist emphasis. Keep Public Sans, square/short-radius geometry, and high-contrast type.

STORY: The Home page introduces the service, then presents real recent published updates at its close. Shared navigation identifies Kalibo MDRRMO Special Operations Unit and provides direct access to Home, About, How It Works, News, reporting, and admin sign-in. Operational screens inherit the material palette but prioritize scan and action.

FIRST VIEWPORT: A compact glass masthead holds the Kalibo emblem and MDRRMO/SOU identity at left, primary links across the middle, and report/action, language, theme, and admin controls at right. A clear hotline alert precedes the public page content. The Home close replaces generic office boilerplate with three dated CMS update rows and a route to the full feed.

FORM: User-directed evolution of the established public-service system. Retain the prior structural reference seed:dab939b1ff988447; the user explicitly pins minimal glassmorphism, quiet neumorphic depth, deliberate gradients, and only a small Neo-Brutalist state/action accent.

FINISH: unreviewed and undocumented is unfinished; this build ends with the finish review, the verdict, DESIGN.md, and every shipping raster carrying its provenance
