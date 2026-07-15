# Tracking & Integrations

Meta Pixel + Conversions API, Google Tag Manager, GA4 (browser + server), Google Search Console,
and Consent Mode v2 — all controlled from **Admin → Settings → Tracking & Integrations**.

Nothing here is baked in at build time. Every value below is edited in the admin panel and takes
effect on the next request. Changing domain means changing **one field**.

---

## Quick start

Everything ships **disabled**, so deploying changes no live behaviour. To go live:

1. Open **Admin → Settings → Tracking & Integrations** (admin accounts only).
2. Paste the values you have (see the field reference below).
3. Press **Test connection** for each one. Fix anything red.
4. Set **Test event code** for Meta, tick **Enabled**, and save.
5. Run `php artisan tracking:verify` and confirm the event appears in Meta's Test Events tab.
6. Clear the test event code so real events start counting.

> **If you already had a Pixel installed the old way** (pasted into *Custom Header Code → Facebook
> Pixel Code*), the panel shows a conflict warning with the detected Pixel ID. Enabling Meta while
> that snippet is still there **double-counts every PageView** — the old snippet has no event ID, so
> it cannot be de-duplicated. Use the **Import ID & clear** button before enabling. Nothing is
> removed automatically.

---

## Field reference

| Field | What it does | Where to get it |
|---|---|---|
| **Pixel ID** | Identifies your pixel for browser + server events | Events Manager → Data Sources → your pixel |
| **Access token** | Authorises server-side Conversions API calls | Events Manager → Settings → Conversions API → Generate access token |
| **Test event code** | Routes events to the Test Events tab instead of live reporting | Events Manager → Test Events |
| **Graph API version** | Meta expires versions roughly yearly | [Graph API changelog](https://developers.facebook.com/docs/graph-api/changelog) |
| **GTM container ID** | Loads your GTM container | Tag Manager → Workspace → container ID (top right) |
| **GA4 Measurement ID** | Identifies the GA4 web stream | GA4 → Admin → Data Streams → your web stream |
| **GA4 API secret** | Authorises Measurement Protocol calls | GA4 → Admin → Data Streams → Measurement Protocol API secrets |
| **GSC verification code** | Proves domain ownership to Search Console | Search Console → Settings → Ownership verification → HTML tag (the `content` value only) |
| **Consent defaults** | Consent Mode v2 state per region | Your call — see [Consent Mode](#consent-mode-v2) |
| **Site URL** | Canonical domain, single source of truth | Your live domain, e.g. `https://example.com` |

**Secrets** (access token, API secret) are encrypted at rest and **write-only**: once saved they are
never shown again, never sent to the browser, and never written to the audit log. Leaving a secret
field blank keeps the stored value — a blank field is the normal state of an untouched form, so it
cannot mean "delete".

To **revoke** a secret, tick the red *Clear the saved token* box under the field and save. Do that
in Meta/Google's UI too — clearing it here stops us using it, it does not invalidate it upstream.

### Secret encryption key

Secrets are encrypted with `SETTINGS_ENCRYPTION_KEY` when set, falling back to `APP_KEY` when not.
The fallback exists so the panel works on a fresh deploy without SSH access.

To isolate the key:

```bash
php artisan tracking:key          # prints a key
# add SETTINGS_ENCRYPTION_KEY=... to .env on the server
```

Rotating the key makes existing encrypted secrets unreadable — **re-paste them in the panel
afterwards**. A secret that cannot be decrypted is treated as "not configured" and logged; it never
takes a page down.

---

## Deduplication: the contract

Meta counts **one** event when the browser and server copies share **both**:

- the same `event_name` (e.g. `Purchase`), and
- the same `event_id`.

That is the entire contract. The implementation honours it like this:

```
TrackingEvents::purchase()
  ├── mints ONE event_id (UUID)
  ├── queues the browser leg  → fbq('track', 'Purchase', {...}, { eventID: <id> })
  └── dispatches the server leg → CAPI { event_name: 'Purchase', event_id: <id> }
```

The browser leg is queued server-side and rendered into the next page, so it always carries the id
the server used. Both legs are sent on purpose: browser-only loses a large share of events to ad
blockers and ITP, server-only loses the browser signals (`fbp`, `fbc`) that drive match quality.
Together with dedup you get the coverage of both without double counting.

**Never** generate a fresh `event_id` client-side for an event that also has a server leg. Pass the
server's id via `trackEvent(name, data, { eventId })`.

### Which events have a server leg

| Event | Browser | Server (CAPI) | GA4 MP |
|---|---|---|---|
| PageView | ✅ | — | — |
| ViewContent | ✅ | — | — |
| Search | ✅ | — | — |
| AddToCart | ✅ | — | — |
| InitiateCheckout | ✅ | ✅ | — |
| AddPaymentInfo | ✅ | ✅ | — |
| **Purchase** | ✅ | ✅ | ✅ |
| **Lead** | ✅ | ✅ | ✅ |
| **CompleteRegistration** | ✅ | ✅ | ✅ |
| Contact | ✅ | ✅ | — |

ViewContent/Search/AddToCart are browser-only by design: they fire on ordinary browsing, so a server
copy would multiply outbound request volume for events that carry little conversion signal.

---

## Event Match Quality (EMQ)

EMQ is Meta's score for how well your events identify real people. It is the difference between ads
that optimise and ads that guess. Read it at **Events Manager → Data Sources → your pixel → Event
Match Quality** — it needs roughly 24–48h of real traffic to settle.

### What we send, and why

**Hashed** (SHA-256, normalised to Meta's spec — see `app/Services/Tracking/PiiHasher.php`):
`em`, `ph`, `fn`, `ln`, `ge`, `db`, `ct`, `st`, `zp`, `country`, `external_id`.

**Unhashed, on purpose** — Meta matches these verbatim and hashing them silently breaks matching:
`client_ip_address`, `client_user_agent`, `fbp`, `fbc`.

Also always sent: `event_source_url` (from `site_url`) and `action_source: "website"`.

### The levers, in order of impact

1. **Logged-in users** send `external_id` plus every profile field on record. This is the single
   biggest lever.
2. **Guests are captured early.** The moment someone types their phone or email at checkout it is
   remembered in the session and attached to **every subsequent event**, not just Purchase. An
   abandoned checkout that later converts still matches.
3. **Orders are the richest source.** Checkout collects name, phone, email and full address even for
   guests, so `Purchase` sends the fullest `user_data` block of any event.
4. **`fbc` is captured on landing.** The pixel cookies `_fbc` itself, but a visitor who arrives from
   an ad and bounces first would lose click attribution — so `fbc` is built from the `fbclid` query
   param immediately, per Meta's format.
5. **Advanced Matching is hashed server-side.** `em`/`ph`/`external_id` are hashed in PHP and
   injected already-hashed into `fbq('init', ...)`. Raw PII never reaches the browser, and because
   both legs use the same `PiiHasher` the hashes cannot drift apart.

### The trap worth knowing

Meta normalises `Valéry` → `valéry` and `정` → `정` — accents and non-Latin scripts are **kept**.
Stripping names to `[a-z]` produces a perfectly valid 64-character hash that Meta accepts and that
matches **nobody**. There is no error; EMQ just quietly drops.

`tests/Unit/Tracking/PiiHasherTest.php` pins every normalisation against Meta's own published
input→hash vectors for exactly this reason. **Do not change `PiiHasher` without running it.**

---

## GA4: browser + server parity

The browser sends events through GTM/gtag. The server additionally sends Purchase, Lead and
CompleteRegistration via the Measurement Protocol.

Parity depends on the **`client_id`**. The server reads it from the visitor's `_ga` cookie, so the
server hit joins the same session as the browser hit rather than inventing a second, unattributed
user. Without the cookie (consent denied, blocker, first hit) a stable per-session id is generated.

### Confirming it in DebugView

1. GA4 → **Admin → DebugView**.
2. Run `php artisan tracking:verify` — it sets `debug_mode: 1`, so the event appears within seconds.
3. For browser events, use the GA4 Debug View Chrome extension or GTM Preview.

Both legs of a real purchase carry the same `event_id` parameter, so you can match them up.

---

## Consent Mode v2

Consent defaults are pushed to `dataLayer` **before any tag loads** — that ordering is required and
is asserted by `tests/Feature/Tracking/TrackingRenderTest.php`.

### How regions map

| Visitor | `ad_storage` / `analytics_storage` | Banner? |
|---|---|---|
| Bangladesh + rest of world | **granted** | No banner |
| EU / EEA / UK / Switzerland | **denied** until they accept | Yes |
| Country unknown | **denied** (fail safe) | Yes |

This store's market is Bangladesh, where GDPR does not apply. Denying by default there would cost
real conversion data for no compliance benefit, so BD customers never see a cookie popup. EU
visitors get a proper Accept/Reject choice.

Region comes from your CDN's country header (`CF-IPCountry`, `X-Geo-Country`, `X-AppEngine-Country`).
**If you are not behind Cloudflare or a proxy that sets one, no country resolves and every visitor
is treated as EU** — i.e. denied by default, banner shown to everyone. If you see that, either put
the site behind Cloudflare or set the defaults to granted globally in the panel, consciously.

All four signals (`ad_storage`, `analytics_storage`, `ad_user_data`, `ad_personalization`) are
editable per region.

### The banner

`resources/views/components/tracking/consent-banner.blade.php` — Alpine, follows the admin colour
palette, renders only for visitors whose default denies something and who have not answered yet.
Accept/Reject calls `gtag('consent', 'update', ...)` and writes a `tracking_consent` cookie on the
dynamic domain. The server reads that cookie to resolve defaults on later requests and to gate
server-side GA4 hits.

**Server-side GA4 is consent-gated in our code.** The browser's Consent Mode signals never reach a
Measurement Protocol call — gtag doesn't see it — so a server hit for a user who denied
`analytics_storage` would bypass their choice entirely. `TrackingEvents::dispatchGa4()` checks
consent and simply does not send. Meta CAPI is not gated by Google's analytics signal.

---

## Domain migration

`site_url` is the single source of truth for `event_source_url`, GA4 `document_location`, canonical
URLs, `og:url`, `sitemap.xml` and `robots.txt`. No cookie is written with a hardcoded domain —
`fbp`, `fbc` and the consent cookie all derive theirs from the request host.

### Code side — one field

1. **Admin → Settings → Tracking & Integrations → Site URL** → new domain → Save.

That's it. If `site_url` and the actual host disagree, the panel shows a red warning at the top, so a
stale setting surfaces immediately instead of silently mis-attributing every event.

### Manual side — things code cannot do

These are Meta/Google-side and **must be done by a human in their UI**:

- [ ] **Meta → Business Settings → Brand Safety → Domains** — add and verify the new domain.
      Until this is done, Aggregated Event Measurement will not work for the new domain.
- [ ] **Meta → Events Manager → Aggregated Event Measurement** — reconfigure your 8 conversion
      events for the new domain.
- [ ] **Google Search Console** — add the new domain as a property and verify it. Paste the new
      verification code into the panel (`gsc_verification_code`). The old property keeps its history;
      it does not transfer.
- [ ] **Search Console → Settings → Change of address** — if the old domain is being retired, use
      this so ranking signals migrate.
- [ ] **Submit the new sitemap** — `https://newdomain/sitemap.xml` in Search Console.
- [ ] **GTM → Admin → Container Settings** — update any referrer/domain allowlist, and any tags with
      a hardcoded domain.
- [ ] **GA4 → Admin → Data Streams** — update the stream URL, and check any referral-exclusion or
      cross-domain settings.
- [ ] **Meta Pixel → Settings** — check the domain allowlist if you use one.

Also worth doing: `APP_URL` in `.env` on the server (used as the fallback when `site_url` is blank).

---

## One-command verification

```bash
php artisan tracking:verify
```

Fires one sample event through **Meta CAPI** and **GA4 Measurement Protocol** sharing a single
`event_id`, and prints the matching `trackEvent(...)` call for the browser leg. Useful flags:

```bash
php artisan tracking:verify --dry                 # build and print payloads, send nothing
php artisan tracking:verify --event=Lead          # a different event
php artisan tracking:verify --email=a@b.com --phone=01712345678
```

Then confirm:

- **Meta** → Events Manager → Test Events (needs a test event code set)
- **GA4** → Admin → DebugView (the command sets `debug_mode`)
- **EMQ** → Events Manager → Event Match Quality, after ~24–48h of real traffic

> Without a test event code, `tracking:verify` sends **real** traffic and the command warns you.

---

## How it fits together

```
Browser                                Server
───────                                ──────
consent defaults (first dataLayer push)
GTM / gtag
fbq('init', pixelId, {hashed em, ph, external_id})
                                       TrackingEvents::purchase($request, $order)
trackEvent(name, data, {eventId}) ◄──── queued browser leg (same event_id)
  ├── fbq('track', name, data, {eventID})
  └── dataLayer.push({event, event_id, ecommerce})
                                       dispatch()->afterResponse()
                                         ├── SendMetaCapiEvent  → graph.facebook.com
                                         └── SendGa4MpEvent     → google-analytics.com/mp/collect
```

Server sends run **after the response is flushed**, so Meta being slow or down can never delay or
break a checkout. They work on the current `sync` queue driver with no worker; switching
`QUEUE_CONNECTION` to `database`/`redis` later makes them fully queued with no code change.

### Where things live

| Path | Purpose |
|---|---|
| `app/Services/Tracking/TrackingSettingsService.php` | Reads settings; caches; degrades to disabled if the DB is unreachable |
| `app/Services/Tracking/TrackingEvents.php` | The one entry point controllers call |
| `app/Services/Tracking/PiiHasher.php` | Meta's normalisation + SHA-256 (**vector-tested**) |
| `app/Services/Tracking/MetaCapiService.php` | CAPI delivery, idempotency, retry/backoff |
| `app/Services/Tracking/Ga4MeasurementProtocolService.php` | GA4 Measurement Protocol |
| `app/Services/Tracking/ConsentResolver.php` | Region → consent defaults; banner decision |
| `app/Services/Tracking/MetaUserData.php` | Builds `user_data` (the EMQ block) |
| `app/Services/Tracking/TrackingContext.php` | IP through proxies, UA, fbp/fbc, cookie domain |
| `app/Services/Tracking/TrackingRedactor.php` | Strips secrets from anything shown or logged |
| `resources/js/tracking.js` | `trackEvent()`, fbc capture, consent update |
| `resources/views/components/tracking/head.blade.php` | Tag injection, in the required order |
| `app/Http/Controllers/Admin/TrackingController.php` | The admin panel |

### Two things that will silently break this if you change them

**1. The cookie exception list.** `bootstrap/app.php` exempts `_fbp`, `_fbc`, `_ga` and
`tracking_consent` from `EncryptCookies`. These are written by JavaScript in plaintext; Laravel
tries to decrypt every incoming cookie and **silently replaces anything it cannot read with null**.
Remove them from that list and: `fbp`/`fbc` stop reaching CAPI (EMQ collapses), every server-side
GA4 hit invents a second unattributed user, and an EU visitor's consent never reaches the server so
the banner reappears on every page forever. Nothing errors. Pinned by
`tests/Feature/Tracking/TrackingRegressionTest.php`.

**2. Error messages must be scrubbed.** Guzzle appends the full request URI — query string included
— to every cURL-level exception message. GA4 requires `api_secret` in the query string, and Test
Connection renders messages straight into the admin's DOM, so an unscrubbed message turns a DNS blip
into a secret disclosure. Anything derived from an exception or an HTTP response must go through
`TrackingRedactor::scrub()` before it reaches a response or a log. (Meta's token avoids this
entirely by going in an `Authorization: Bearer` header rather than the URL.)

### Reliability

- **Idempotent**: a successful `event_id` is remembered for an hour, so a retry cannot double-count.
  It is recorded only *after* Meta confirms, so a failed send is never silently swallowed.
- **Retries**: 3 attempts with exponential backoff on network errors, 5xx and 429. A 4xx (bad payload
  or token) is not retried — it cannot succeed and would just burn requests.
- **Logged**: event name, event id, HTTP status and attempt count. **Never** tokens or raw PII.
- **Non-fatal**: every call site is wrapped. Tracking cannot break an order, registration or contact
  submission that already succeeded. If the settings table is unreachable, pages render with tracking
  off and a logged warning.

---

## Adding a new event

```php
// Server-side, with a browser leg and dedup:
app(TrackingEvents::class)->purchase($request, $order);

// Browser-side only:
window.trackEvent('AddToCart', {
    currency: 'BDT',
    value: 499,
    content_type: 'product',
    content_ids: ['123'],
    contents: [{ id: '123', quantity: 1, item_price: 499 }],
    num_items: 1,
});
```

Use `trackEvent()` — never call `fbq` or push to `dataLayer` directly. It is what guarantees the
Meta and GA4 copies stay consistent and carry a shared event id.

---

## Testing

```bash
php artisan test tests/Unit/Tracking tests/Feature/Tracking
```

Covers: Meta's official hash vectors, settings caching/invalidation/degradation, admin RBAC, secrets
never rendered or logged, the dedup contract, CAPI retry/idempotency, GA4 parity and consent gating,
consent ordering and regional defaults, sitemap/robots following `site_url`, and — importantly — that
the storefront renders nothing when tracking is off and the legacy `fb_pixel` snippet still works.
