# Mission Control — Admin Task Pipeline (Design)

**Date:** 2026-07-09 · **Status:** Approved by user (co-designed in Bangla)

## Overview

Replace the static admin "Docs" page (image size guide) with **Mission Control** — a premium,
animated task pipeline. Any admin creates tasks (title, description, priority, optional deadline
and reference image) for a designated executor — the user with username `rajin` — who alone
controls task statuses from the same admin panel. Includes a cinematic "dispatch" animation on
create, live status updates via polling, a mixed timeline + comment thread per task, an SMS
reminder (SSL Wireless), and a 5-minute auto transition from Approved to In Progress. The page
uses a dark "cockpit" aesthetic scoped to this section only; the rest of the admin panel stays
light.

## What is removed

- Route `admin/setting/docs` — `routes/admin.php:320`
- `SettingController::docs()` — `app/Http/Controllers/Admin/Ecommerce/SettingController.php:689-692`
- View `resources/views/admin/e-commerce/docs.blade.php`
- Sidebar "Docs" item — `resources/views/layouts/admin/sidebar.blade.php:300-303` — replaced by
  the Mission Control item (radar/satellite icon).

## Data model

**`tasks`**

| column | type | notes |
|---|---|---|
| id | pk | |
| title | string(150) | required |
| description | text | required |
| image | string, nullable | stored under `public/uploads/tasks/` |
| priority | string | `low` \| `normal` \| `urgent`, default `normal` |
| status | string | `awaiting_review` \| `under_review` \| `approved` \| `in_progress` \| `delivered`, default `awaiting_review` |
| due_date | date, nullable | |
| approved_at | timestamp, nullable | set when status becomes `approved`; drives 5-min auto-flip |
| completed_at | timestamp, nullable | set when status becomes `delivered` |
| last_reminded_at | timestamp, nullable | drives reminder throttle |
| created_by | FK → users.id | |
| timestamps | | |

**`task_activities`** — one stream powering both the status timeline and the comment thread:

| column | type | notes |
|---|---|---|
| id | pk | |
| task_id | FK → tasks.id, cascade delete | |
| user_id | FK → users.id, nullable | null = system event (e.g. auto-flip) |
| type | string | `status` \| `comment` |
| status | string, nullable | the new status when `type = status` |
| body | text, nullable | comment body when `type = comment` |
| timestamps | | |

Models: `Task`, `TaskActivity` (`$guarded = ['id']` per project convention). `Task` has
`activities()` (hasMany, latest first), `creator()` (belongsTo User). Status labels, colors,
and icon keys live as constants/helpers on `Task`.

## Status pipeline

| key | label | set by | visual |
|---|---|---|---|
| — | Dispatching… | client-side theatre only (~2 s overlay after successful create) | paper-plane flight + signal pulse |
| `awaiting_review` | Awaiting Review | system, on create | amber radar/sonar pulse; subtext "Delivered to Rajin — awaiting acknowledgment" |
| `under_review` | Under Review | rajin | blue scanning animation |
| `approved` | Approved | rajin (sets `approved_at`) | emerald shield-check pop |
| `in_progress` | In Progress | **auto**, 5 min after `approved_at` (rajin may also set manually) | violet gear spin / shimmer |
| `delivered` | Delivered | rajin (sets `completed_at`) | gold check + one-shot confetti |

- Rajin may move a task to **any** status (non-linear allowed). Every change writes a
  `task_activities` row.
- "Dispatching…" is not a DB status — the task is saved instantly as `awaiting_review`; the
  overlay is pure presentation.
- **5-minute auto-flip (lazy, no cron):** whenever tasks are fetched (page load or poll feed),
  any task with `status = approved` and `approved_at <= now() - 5 min` is updated to
  `in_progress` with a system activity row. Works on shared hosting.

## Access rules

- All routes sit behind the existing admin middleware (`role_id == 1`).
- **Executor check:** `username === 'rajin'` (and admin role). Implemented once — e.g. a Gate
  `control-tasks` or a `User::isTaskExecutor()` helper — never inline string checks scattered
  around.
- Any admin: create tasks, comment, send SMS reminder, view everything. (The UI hides the
  reminder button for rajin himself — reminding yourself is pointless — but the server does not
  special-case it beyond the throttle.)
- Edit / delete a task: any admin, but **only while `status = awaiting_review`** (once Rajin
  starts, the task is locked).
- Status changes: **executor only** (403 otherwise).
- **Seeder:** `RajinSeeder` (registered in `DatabaseSeeder`) creates user `rajin`
  (role_id 1, default password to be changed after first login). Phone number is read from a
  `RAJIN_PHONE` env value with a placeholder fallback — the SMS reminder reads the live value
  from `users.phone`, so it can also be corrected later from the profile. Skips creation if the
  username already exists.

## Routes (`routes/admin.php`)

Controller: `app/Http/Controllers/Admin/Ecommerce/MissionControlController.php` (matches the
existing controller location convention).

| method | uri | action | notes |
|---|---|---|---|
| GET | `admin/mission-control` | index | the page |
| GET | `admin/mission-control/feed` | feed | JSON for 12 s polling; also runs the 5-min auto-flip |
| POST | `admin/mission-control` | store | create task (multipart, image optional) |
| PUT | `admin/mission-control/{task}` | update | guard: `awaiting_review` only |
| DELETE | `admin/mission-control/{task}` | destroy | guard: `awaiting_review` only |
| PATCH | `admin/mission-control/{task}/status` | updateStatus | executor only |
| POST | `admin/mission-control/{task}/comment` | comment | any admin |
| POST | `admin/mission-control/{task}/remind` | remind | SMS, throttled |

## SMS reminder (SSL Wireless)

- Reuses the existing raw-cURL pattern (`smsplus.sslwireless.com/api/v3/send-sms`, as in
  `app/Http/Controllers/Auth/RegisterController.php:103-112`), sent to rajin's `users.phone`.
- Message: `Mission Control: Task #{id} "{title}" ({priority}) is {status label}. Please check
  the admin panel.`
- **Throttle:** max 1 reminder per task per 30 minutes, enforced server-side via
  `tasks.last_reminded_at` (friendly JSON error when hit); button shows a disabled countdown
  client-side.
- SMS API failure is caught and surfaced via `notify()->error(...)`; it never blocks the task
  flow.

## Frontend / UX

- One Blade page `resources/views/admin/mission-control/index.blade.php` extending
  `layouts.admin.app`, built with Tailwind v4 + Alpine.js. **Dark cockpit** styling is scoped to
  the page container (dark glass panels, subtle grid backdrop, glowing status colors) while the
  admin chrome (sidebar/header) stays light.
- **Create panel:** title, description, priority (Low/Normal/Urgent), optional due date,
  optional image with client-side preview. Upload stored in `public/uploads/tasks/`.
- **Dispatch theatre:** on successful store → full-screen overlay, phase 1 "Transmitting your
  task to Rajin…" (~2 s) → phase 2 "Awaiting Review" radar pulse with the placeholder subtext →
  overlay dismisses, new card animates into the board.
- **Board:** tabs **Active** / **Delivered**. Card shows title, priority badge (Urgent = pulsing
  red), animated status pill, due date with **Overdue** state, image thumbnail (lightbox),
  relative created time, Send Reminder button (hidden for rajin), status controls (rajin only).
- **Drawer per task:** full description, image, mixed timeline (status events + comments in one
  stream, e.g. "Rajin approved this task · 2m ago"), comment box.
- **Polling:** `fetch` of the feed every 12 s; statuses/badges update in place with smooth
  transitions — the admin sees Rajin's changes live without refresh.

### Animation system (premium motion rules)

- **Infinite motion rule:** every status visual loops forever while on screen — CSS keyframes
  with `infinite`, never a one-shot animation that freezes. As long as the admin is looking at a
  task, its status icon breathes/pulses/spins. (Micro-celebrations like the Delivered confetti
  fire once on the *transition*, but Delivered then settles into a subtle infinite glow.)
- **Live activity ticker (AI-console style):** beneath each status label sits a small
  shimmer-gradient text line — styled like an AI assistant's streaming "thinking" indicator
  (animated gradient sweep + typewriter reveal). It cycles through a per-status set of
  micro-status lines, one after another, fading/sliding in an infinite loop:
  - *Dispatching…* — "Encrypting payload…" → "Establishing secure channel…" → "Transmitting to
    Rajin…"
  - *Awaiting Review* — "Signal delivered" → "Pinging Rajin's console…" → "Awaiting
    acknowledgment…"
  - *Under Review* — "Rajin is examining the brief…" → "Scanning reference material…" →
    "Assessing scope…"
  - *Approved* — "Green light received" → "Scheduling execution window…" → live countdown (see
    below)
  - *In Progress* — "Rajin is on it…" → "Work in motion…" → "Progress compiling…"
  - *Delivered* — "Mission accomplished ✦" → "Delivered & verified"
  - Line sets live in one JS config object so copy can be tuned in a single place.
- **T-minus countdown:** while a task is Approved, the ticker includes a live countdown to the
  auto-start ("Auto-starts in 04:32"), computed client-side from `approved_at`. At zero the
  client optimistically flips the card to In Progress; the next poll confirms from the server.
- The dispatch overlay uses the same ticker component for its two phases.
- **Sidebar:** "Mission Control" item replaces Docs; when the logged-in user is rajin, a badge
  shows the count of `awaiting_review` tasks.

## Validation & error handling

- Form Request: `title` required, max 150; `description` required; `image` nullable image,
  max 5 MB; `priority` in `low|normal|urgent`; `due_date` nullable date, today or later.
- Executor-only actions → 403. Edit/delete after work started → 422 with a clear message.
- Reminder throttle hit → 429-style JSON with remaining wait time.

## Testing (Pest)

- Create task with and without image; validation failures.
- Non-rajin admin gets 403 on status change; rajin succeeds and an activity row is written.
- Edit/delete rejected once status ≠ `awaiting_review`.
- Approved task auto-flips to `in_progress` after 5 minutes on feed fetch (time travel).
- Comments post from both creator and rajin.
- Reminder throttle enforced (second call within 30 min rejected).
- Old docs route no longer exists.

## Out of scope

WhatsApp Business API, websockets/broadcasting, email notifications, multiple executors,
assigning tasks to arbitrary users, task categories/labels.
