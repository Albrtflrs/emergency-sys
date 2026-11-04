# Public reporting and tracking

The public report and tracking screens use the landing page's neutral canvas, `vibrant.jpg` background, light/dark overlay, rounded white surfaces, and blue/gold accents. The report is a guided in-page flow; moving between steps, capturing a photo, submitting, and opening the confirmation do not navigate away.

## Reporting flow

1. **Incident:** choose an active incident type and describe what happened.
2. **Location and photo:** type a barangay, street, or landmark. Optionally use **Use my current location** to ask the browser for coordinates. The typed address remains required and location permission is optional.
3. **Contact:** provide reporter name and a Philippine-format phone number. The on-behalf toggle reveals relationship and contactability fields.

The camera icon opens the in-page camera preview. A photo is optional and can only be added through this capture control; there is no file picker. Captured images are resized to a maximum 1,600 px edge, JPEG encoded, limited to 4 MB by the API, and stored on Laravel's private local disk. The reporter may type a location or use the browser's location control to pin coordinates; either method is enough. Reports can be submitted without camera permission.

Submission is sent as one multipart request. The browser disables repeat clicks and reuses its idempotency key when a request is retried. A confirmed response displays the reference, expiry, tracking URL, copy/share actions, and a **Submit another report** control. A same-key retry returns the same report with a fresh tracking link and revokes the prior token.

## Tracking flow

Tracking requires both the reference code and private token. The page shows the current status, recorded status changes, expiry countdown, attached photo (when present), and a message thread. It refreshes status and messages in place while the page is open. Contact details are never returned to the tracking page or status API.

Staff status changes are stored in `report_status_events` and appear in the reporter timeline. A report photo is readable only from a valid tracking link or an authenticated staff/admin session. Invalid, expired, or revoked tracking tokens cannot read status, messages, or photos.

## Local verification

Use the isolated testing schema from [manual-testing.md](manual-testing.md). Apply new schema changes with the migration-only database connection:

```powershell
php artisan migrate --env=testing --database=mysql_migrate --force
```

The regular testing connection is intentionally not granted schema changes. The manual router continues to connect to `emergency_sys_test` after migrations have been applied.

## Current limits

- A typed location or a current-position pin is required. The location control uses browser geolocation; a map canvas and draggable map pin are not implemented.
- One camera-captured photo is supported per report; there is no multi-photo gallery or asynchronous upload queue.
- The public timeline shows submitted/status-change events. There is not yet a separate public staff-update editor.
- Camera and geolocation controls require a secure browser context (HTTPS or localhost); either permission can be declined without blocking text reporting.
