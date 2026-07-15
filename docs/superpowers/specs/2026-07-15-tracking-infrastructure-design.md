# Tracking & Integrations Infrastructure — Design

**Date:** 2026-07-15
**Status:** Approved (decisions locked in batch, see "Locked decisions")
**Scope:** Meta Pixel + Conversions API, GTM, GA4 (client + Measurement Protocol), Google Search
Console, Consent Mode v2, and a runtime admin panel controlling all of it.

## Goal

Every tracking value (pixel IDs, tokens, container IDs, verification codes, canonical domain,
consent defaults) is editable from the admin dashboard and takes effect immediately. No redeploy,
no code edit, ever. A domain migration requires changing **one field** in the panel — everything
else derives from it.

## Locked decisions

These were decided up front so implementation runs without further confirmation.

| # | Decision | Choice |
|---|----------|--------|
| 1 | Existing `fb_pixel` raw snippet | Ship **OFF**; panel warns on conflict; never auto-deleted. Admin presses "Import ID & clear". |
| 2 | Consent Mode defaults | **Granted** for BD/rest-of-world, **denied** for EU/EEA/UK, **denied** for unknown IP (fail-safe). |
| 3 | Consent banner | Build minimal Alpine banner, shown **only** to denied-default visitors. |
| 4 | Secret encryption | `SETTINGS_ENCRYPTION_KEY` when set, silent fallback to `APP_KEY`. |
| 5 | Git | Feature branch → PR. **Not merged** (merge = production deploy). |
| 6 | Credentials | Pasted by admin later. Ships disabled with empty fields. |
| 7 | Existing `add_to_cart` dataLayer push | Replaced by central `trackEvent()`, identical GA4 payload. |
| 8 | Server sends | `dispatch()->afterResponse()` — no infra change, never blocks checkout. |

**Overriding safety principle:** merging this PR must change **zero** live behavior. Every
integration ships disabled; the old `fb_pixel` path keeps rendering untouched until an admin
deliberately switches over.

## Discovered stack (verified by reading code, not assumed)

- **Laravel 12** (`composer.json` — note `AGENTS.md` says 11; stale), PHP 8.2, Blade + Vite +
  Tailwind v4 + Alpine. MySQL in production, SQLite locally. `CACHE_STORE=file`,
  `QUEUE_CONNECTION=sync`, `SESSION_DOMAIN=null` (already dynamic — good).
- **RBAC:** `AdminMiddleware` (`app/Http/Middleware/AdminMiddleware.php:20`) gates on
  `Auth::user()->role_id == 1`, aliased `admin` in `bootstrap/app.php:20`. Roles seeded
  `1=Admin, 2=Vendor, 3=User`. This is a real admin-only gate — **reuse it**, do not invent a
  parallel role system.
- **Settings idiom:** `Setting` is key→value (`app/Models/Setting.php`), `setting('name')` helper,
  `Cache::rememberForever('settings')`, invalidated on save.
- **Integration idiom (style template):** `app/Services/PathaoCourierService.php` — static methods,
  `Http::acceptJson()->post()`, `Cache` for tokens, config read via `setting()`. Credentials stored
  as **plain** Setting rows; no encryption exists anywhere in the repo today.
- **Storefront head:** `resources/views/layouts/frontend/app.blade.php:4-11`.
- **Existing analytics:** `{!! setting('fb_pixel') !!}` (line 8) and `{!! setting('header_code') !!}`
  (line 10), both raw HTML. Plus one hand-rolled GA4 `add_to_cart` push at
  `resources/views/components/product-grid-view.blade.php:298`. **No consent banner exists.**
- **Currency:** BDT. Country default `setting('COUNTRY_SERVE') ?? 'Bangladesh'`.
- **Tests:** Pest, `RefreshDatabase` on `Feature`, global `adminUser()` helper in `tests/Pest.php`
  returning `User::factory()->create(['role_id' => 1])`. `Http::fake()` is the established pattern
  (`tests/Feature/PathaoCourierTest.php`).

### Conversion flows (verified)

All five order paths (`orderStore`, `orderBuyNowStore`, `orderStore_guest`, `orderStore_minimal`,
`orderBuyNowStore_minimal`) funnel through **one** private method
`OrderController::createOrder()` (`app/Http/Controllers/Frontend/OrderController.php:489`) and all
render `frontend.order_success`. That single chokepoint is where Purchase fires server-side —
mirroring the existing `OrderSms::send($order)` call at line 587.

| Event | Route / hook | Kind |
|-------|--------------|------|
| ViewContent | `product/{slug}` → `ProductController@productDetails` | page render |
| Search | `product/search` → `ProductController@productSearch` | page render |
| AddToCart | `POST add/cart` → `CartController@addToCart` | AJAX fetch |
| InitiateCheckout | `GET checkout` → `CheckoutController@checkout` | page render |
| AddPaymentInfo | checkout payment-method selection | client-side |
| Purchase | `OrderController::createOrder()` + `frontend.order_success` view | POST → view |
| Lead | `POST /incomplete-lead/store` → `IncompleteLeadController@store` | AJAX |
| CompleteRegistration | `POST user/register` → `RegisterController@register` | form POST |
| Contact | `ContactController` | form POST |

Real column names — Order: `order_id` (`ORD-XXXXXXXX`), `id`, `total`, `subtotal`,
`shipping_charge`, `discount`, `email`, `phone`, `first_name`, `last_name`, `district`, `town`,
`post_code`, `country`. OrderDetails: `product_id`, `qty`, `price`, `total_price`, `title`.
Cart (`App\Core\ShoppingCart\Facades\Cart`): `Cart::content()` items expose `id`, `name`, `qty`,
`price`, `subtotal`, `options`.

## Architecture

Each unit has one purpose and is independently testable.

### Data

**`tracking_settings`** — single-row table, typed columns, `encrypted` casts on secrets.

*Why a dedicated table and not `Setting` key→value rows (the repo idiom)?* `Setting::cached()`
loads **every** setting into one `rememberForever` array that `setting()` exposes to all Blade
templates. Storing `meta_access_token` there would place a decrypted secret in that shared blob and
make it reachable from any view — exactly what the brief forbids. A separate table with `encrypted`
casts keeps secrets out of that path entirely, gives type safety, and lets the audit log diff
fields. This is a deliberate, justified divergence from the `Setting` idiom.

Columns: `meta_pixel_id`, `meta_access_token` (enc), `meta_test_event_code`, `meta_api_version`,
`meta_enabled`; `gtm_container_id`, `gtm_enabled`; `ga4_measurement_id`, `ga4_api_secret` (enc),
`ga4_enabled`; `gsc_verification_code`, `gsc_enabled`; `consent_mode_enabled`,
`consent_banner_enabled`, `consent_default_row` (json), `consent_default_eu` (json); `site_url`.

**`tracking_setting_audits`** — `user_id`, `field`, `old_value`, `new_value`, `created_at`. Secret
fields record `'••••••'` for both values: the *fact* and *time* of change plus *who* is logged,
never the value.

### Services (`app/Services/Tracking/`)

- **`TrackingSettingsService`** — DB read, short-TTL cache, invalidate on save. `publicConfig()`
  returns the safe subset only (never secrets). Wraps reads in try/catch: on DB/cache failure it
  returns a disabled config and logs a warning, so pages still render.
- **`PiiHasher`** — Meta's exact normalization + SHA-256 per field. Pure, no deps, heavily unit
  tested against Meta's documented vectors.
- **`TrackingContext`** — resolves `client_ip_address` (through proxy headers), `client_user_agent`,
  `fbp`, `fbc`, `event_source_url` from `site_url` + path.
- **`ConsentResolver`** — request → region → default consent state. Uses `CF-IPCountry` when
  present, else a static EU/EEA/UK country check, else denied.
- **`MetaCapiService`** — builds + sends to `graph.facebook.com/{version}/{pixel}/events`.
  Idempotency via cache keyed on `event_id`; 3 attempts with exponential backoff. Logs event name,
  event_id, HTTP status, retry count — **never** tokens or unhashed PII.
- **`Ga4MeasurementProtocolService`** — sends to `/mp/collect`, consent-gated.
- **`TrackingEvents`** — thin façade the controllers actually call (`TrackingEvents::purchase($order)`),
  matching the existing `OrderSms::send($order)` idiom.

### Jobs

`SendMetaCapiEvent`, `SendGa4MpEvent` — dispatched via `dispatch()->afterResponse()`. Works on the
current `sync` driver without blocking the response; becomes fully queued for free if
`QUEUE_CONNECTION` ever changes.

### Browser (`resources/js/tracking.js`)

Load order is strict and matters:

1. Consent Mode v2 defaults — **first** `dataLayer` push, before any tag.
2. GTM container (if enabled).
3. `fbq('init', pixelId, advancedMatchingParams)`.
4. PageView / page_view.

`trackEvent(eventName, customData, options)` is the single entry point everywhere: generates (or
accepts) a UUID `eventId`, fires `fbq('track', name, data, {eventID})`, pushes the GA4-shaped
equivalent to `dataLayer`, returns the `eventId` so the server leg can dedupe against it.

Advanced Matching: for logged-in users, `em`/`ph`/`external_id` are hashed **server-side** and
injected into the page as already-hashed values — the browser never sees raw PII and never
implements its own hashing, so client and server hashes cannot drift.

### Blade

- `resources/views/components/tracking/head.blade.php` — consent init, GTM, pixel, GSC tag.
  Included in `layouts/frontend/app.blade.php` **before** existing `fb_pixel`/`header_code` lines,
  which stay exactly where they are.
- `resources/views/components/tracking/consent-banner.blade.php` — Alpine, brand palette, rendered
  only when the resolved default is denied.

### Admin

`App\Http\Controllers\Admin\TrackingController` (index/update/test/audit), view
`resources/views/admin/e-commerce/setting/trackingIndex.blade.php`, routes in `routes/admin.php`
under the existing `admin` middleware, sidebar link added next to Pathao.

Secrets use masked write-only inputs: existing values are **never** re-rendered. A blank field
means "leave unchanged"; a sentinel clears it.

## Domain-migration safety

`site_url` is the single source of truth for `event_source_url`, GA4 `document_location`, canonical
tags, `og:url`, sitemap.xml, and robots.txt. Cookies derive their domain from the request host —
never hardcoded. The admin panel shows a prominent warning when `site_url` doesn't match the actual
request host, so a stale setting after migration surfaces immediately instead of silently breaking.

`TRACKING.md` documents what code **cannot** do: re-add the domain in Meta Business Settings, re-verify
the new Search Console property, update the GTM referrer allowlist.

## Testing

- **`PiiHasherTest`** (unit) — every normalization rule against Meta's officially documented test
  vectors. A subtle bug here silently tanks EMQ with no visible error, so this is asserted, not assumed.
- `TrackingSettingsServiceTest` — caching, invalidation, secrets excluded from `publicConfig()`,
  graceful degradation when the DB throws.
- `TrackingAdminTest` — non-admin (`role_id != 1`) blocked, admin allowed, secrets never re-rendered,
  audit rows written with masked secret values.
- `MetaCapiServiceTest` / `Ga4MpTest` — `Http::fake()`, payload shape, dedup/idempotency, retry
  backoff, consent gating.
- `TrackingRenderTest` — disabled by default renders nothing; existing `fb_pixel` still renders.
- Full existing suite must stay green.

## Verification

`php artisan tracking:verify` fires one full sample event — client-shaped payload, matching Meta
CAPI call, and matching GA4 MP call, all sharing `event_id`/`client_id` — so the whole pipeline is
confirmable in one command, then checked in Meta Test Events and GA4 DebugView.

## Out of scope

Removing `header_code` (unrelated, still used). Rewriting existing Setting rows. Fixing the real
production DB credentials committed in `.env.example` — flagged separately, not touched here.
