# Eureka — Project Status & Full Review

_Last full review: 2026-07-05 (branch `claude/koro-ai-puro-review-1yb5kv`, from HEAD `fdc54f1`)._

This is the single source of truth for **where the project stands today**: what works, what is
partial, what is broken, the visual state on desktop + mobile, and the leftover old-design cruft to
remove. It was produced by (1) a read-only backend functional audit, (2) a read-only frontend/views
audit, and (3) a live visual pass — the app was booted on SQLite with seeded demo data and every
main storefront page was screenshotted at desktop (1440px) and mobile (390px).

The brand currently rendered by the storefront is **"Anas Luxyworld"** (a lighting/lamp store) built
on the Eureka multi-vendor platform.

---

## 1. Current stage (one paragraph)

Eureka is a **functional storefront MVP** on a codebase that was recently cleaned after a malware
incident (`Frontend/OrderController` was restored in `84ecda9`). The **customer-facing storefront
redesign (Tailwind v4 "luxury" theme) is largely complete and genuinely polished** — home, category,
single-product, cart, and checkout are modern and responsive. The **cash-on-delivery order flow works
end to end.** What is *not* done: **online payment confirmation (UddoktaPay) is missing**, the
**multi-vendor split is effectively inert** (`multi_order` never populated), **vendor withdrawals are
broken/exploitable**, and a **set of security findings from `docs/CODE_REVIEW.md` remain open.**
Layered under the new Tailwind theme, the **entire legacy Bootstrap / AdminLTE / jQuery stack and ~50
unused plugin folders still ship** — this is the "unnecessary old-design cruft" to remove step by step.

---

## 2. Feature status matrix

Legend: 🟢 fully working · 🟡 partial / has gaps · 🔴 broken / dead · ⚪ needs runtime/config check

### Storefront (customer)
| Feature | Status | Notes |
|---|---|---|
| Product browsing (category/brand/collection/sub/mini) | 🟢 | `Frontend/ProductController` all methods wired |
| Single product detail | 🟢 | Redesigned, responsive, gallery + colour swatches + accordions |
| Product search (Scout) | ⚪ | `Product::search()` depends on Scout driver/index config |
| Cart | 🟢 | Add/update/remove/compare OK; per-attribute/colour pricing is commented out (`CartController` addToCart:26 → attribute price = 0) |
| Coupons | 🟡 | Session-based; stale `coupon` session not cleared when cart empties |
| Checkout (COD) | 🟢 | Guest vs role-2/3 gate correct; empty cart → redirect home |
| Orders (create/list/invoice/cancel/return/review/download) | 🟢 | Restored full 437-line `Frontend/OrderController` |
| **Online payment (UddoktaPay)** | 🟢 | Confirmation flow implemented: `success`/`success2`/`fail`/`webhook` verify each invoice via `verify_payment()` and idempotently mark the order paid (`pay_staus=1`) only when COMPLETED and the amount covers the total; webhook is API-key authenticated and fails closed. *(End-to-end against the live gateway still needs real UddoktaPay credentials to exercise.)* |
| Reviews | 🟢 | Recomputes product average |
| Wishlist | 🟢 | Works (IDOR on delete — see security) |
| Blog | 🟡 | Works, but `$request->descripiton` typo (blogControler:54,122,152) nulls the description unless the form field is literally misspelled; delete path missing `/` so files never unlinked |
| Classified ads | 🔴 | Public `/classic/` listing **always empty** — `all()` filters `status='12'` but ads are created `status='0'` and never promoted (adsController:127) |
| Account dashboard / profile | 🟢 | Redesigned |
| Contact / tickets / newsletter / campaigns | 🟢 | All wired |

### Vendor portal
| Feature | Status | Notes |
|---|---|---|
| Products CRUD | 🟡 | **Edit swaps attribute `qnty`↔`price`** (Vendor/ProductController:503-511) → data corruption; leftover `echo $attribute;` :213 |
| Orders | 🟡 | Status changes split across two models; **`multi_order` not populated at checkout** so per-vendor sub-status updates 0 rows |
| Withdrawals | 🔴 | **Negative-amount balance inflation, still unfixed** (WithdrawController:20-52, no validation); `cancel()` refund is dead code + wrong ledger |
| Dashboard / profile | 🟢 | OK |

### Admin back office
| Feature | Status | Notes |
|---|---|---|
| Catalog (product/category/brand/etc.) | 🟢 | 928-line ProductController resolves; prior `updateImage` data-loss bug no longer present |
| Orders / refunds | 🟡 | `refund()` undefined `$id` :673; `refund_two()` no idempotency :688; `partialStatus` double-counts on replay :531 |
| POS | 🔴 | `Admin/POSController.php` is an **empty file**; all 5 routes commented out |
| Custom orders | 🟡 | Fragile undefined coupon vars |
| Customers / vendors / dashboard | 🟢 | OK (dashboard fixes documented in `DASHBOARD_AUDIT.md`) |
| Settings | 🟡 | `update()` type 1 validates but **persists nothing** (:36-64) |
| Staff / roles | 🟡 | Edit form renders but **no update handler/route** — dead end |

### Cross-cutting
| Feature | Status | Notes |
|---|---|---|
| Auth login/register | 🟡 | Works, but admin redirect broken (undefined `$this->redirectAdmin`); leftover `dd`/hardcoded OTP/SMS token |
| Social OAuth | 🟡 | Happy path OK; empty catch → fatal on failure; **active `dd($user)` in orphaned `handleFacebookCallback` (LoginController:273)** |
| Fraud checker (BDCourier) | 🟢 | Works; hardcoded key fallback + TLS-off (security) |
| Courier (Steadfast) | 🟡 | `return back()` before success check; no try/catch → 500 on API error |
| Notifications (FCM) | 🟡 | Now auth-gated + validated, but hardcoded legacy FCM key on a decommissioned endpoint → likely dead at runtime |
| Live chat / SMS | 🟢 | DB-polling, no websockets |

### Dead routes → 500 if hit
`login/vendor` (`HomeController::vendorLogin`), and vendor `deleteImage` / `pre` / `partials` /
`partialStatus` / `delete` — methods don't exist (routes/vendor.php:17,50,54,55,59).

---

## 3. Security posture

Detailed findings live in **`docs/CODE_REVIEW.md`** (41 findings: 6 Critical, 27 High). Status of the
highest-impact items at current HEAD:

**Fixed:** contact-form upload validation ✓ · unauthenticated `/cache` artisan route (commented out) ✓
· `/send-notification` open blast (now `admin` middleware + validation, `dd` removed) ✓ · admin
`updateImage` data-loss (method removed) ✓

**Still open (priority):**
- 🔴 Vendor withdrawal **negative-amount inflation** — direct theft vector (WithdrawController:20-52)
- 🔴 **IDOR set** — blog status/delete/update, classified-ads delete/edit/update, wishlist delete (all use unscoped `Model::find()`)
- 🔴 **`dynamic_price` cart tampering** — now live again after OrderController restore (buyProduct:32 → cart price → order subtotal, no server-side check)
- 🟡 Admin refund / partial-payment **non-idempotent** (replayable wallet credits)
- 🟡 Hardcoded **FCM + BDCourier API keys** in source (rotate + relocate)
- 🟡 Stored/reflected **XSS** via `{!! !!}` sinks on vendor/account content (63 raw sinks across 17 storefront views)

> Note: the committed-secrets findings (DB password, `APP_KEY` in `bootstrap/cache/config.php`) require
> **history scrubbing + credential rotation** regardless of working-tree state — deletion alone does not
> remediate git history.

---

## 4. Visual review — desktop & mobile

Booted on SQLite + demo seed; screenshotted every main page at 1440px and 390px.

**Overall: the redesigned storefront pages look good and are responsive.** No page had horizontal
overflow at 390px (`scrollWidth == clientWidth` everywhere). Highlights:
- **Home** — hero + per-category product grids (2-col on mobile), CTA banner, newsletter, footer. Clean.
- **Category** — "Customer Favorites" header, Filters button, product cards with ratings/price, **sticky mobile bottom nav** (Home / Login / Category / Cart). Nicely done.
- **Single product** — luxury layout: gallery, colour swatches, price, Add-to-Cart / Buy-It-Now, collapsible Design Story / Technical / Shipping / Warranty. Polished.
- **Cart** — modern empty state + Order Summary card (subtotal / shipping / tax / coupon / total).

**Visual issues found:**
| Issue | Where | Severity |
|---|---|---|
| Legacy account sub-pages still old Bootstrap tables with **no overflow wrapper** → mobile risk | compare, wishlist, order, cashout, download, myrefer, payform, returns_order, ticket, ads/list | Medium |
| Recurring JS error `Cannot read properties of null (reading 'getAttribute')` on nearly every page | csrf-token lookups in `components/product-grid-view.blade.php:365`, `product-list-view.blade.php:227` (no `?.` optional chaining, unlike layout app.blade.php:343) | Low–Medium |
| Build was **blocked** — `vite.config.js` fetched `bunny('Instrument Sans')` (a remote font) at build time, and that font is not used in `app.css` (which uses Muli / Source Sans Pro). Removed in this branch. | `vite.config.js` | Fixed here |
| Three overlapping icon systems + remote CDN fonts loaded per-page (blocked offline, slow online) | see §5 | Medium |

Screenshots for this review are in the session scratchpad (not committed).

---

## 5. Old-design cruft to remove (step by step)

The Tailwind v4 redesign sits **on top of** the full legacy stack. Removal order, safest first:

**Tier 1 — dead files, zero references (safe to delete now):**
- `resources/views/frontend/checkout.blade.php.backup-debug` (stray backup)
- `public/assets/frontend/js/main_old.js` (superseded by `main.js`)
- Unreferenced storefront CSS: `flexslider.css`, `image-zoom.css`, `jquery-ui1.css`, `unministy.css`
- Unreferenced storefront JS: `city.js`, `image-zoom.js`, `jquery-ui.js`, `jquery.flexslider.js`, `popper.min.js`
- **~50 unused AdminLTE plugin folders** in `public/assets/plugins/` (keep only: datatables\*, pdfmake, dropify, jszip, summernote, select2(+theme), bootstrap, file-uploader, jquery, fontawesome-free, moment, dropzone). Everything else is dead (bootstrap-colorpicker, chart.js, codemirror, fullcalendar, ion-rangeslider, sweetalert2, toastr, jqvmap, flot, etc.)
- `vite.config.js` bunny font — **done in this branch**

**Tier 2 — remote/CDN dependencies to self-host or drop:**
- FontAwesome **Pro** kit CDN (`style.blade.php:9`) — storefront already loads FA5 `all.css` + icofont; three icon fonts overlap
- Boxicons via `unpkg` (vendor & admin layouts); Google Fonts + Google Translate (frontend); Material Icons, second jQuery, FA4.7, cdnjs Dropify/colorpicker scattered per-view
- Per-view Google Font `@import` in ~20 storefront views → consolidate into one pipeline

**Tier 3 — mixed styling (needs widget replacement first, do last):**
- Storefront still ships `bootstrap.min.css` + jQuery + slick + moment + toast under Tailwind (`style.blade.php`, `script.blade.php`) — removing requires porting `main.js`, sliders, `$.toast`
- Vendor/admin keep AdminLTE Bootstrap base **by design** for un-migrated DataTables/select2/summernote widgets

**Also delete (dead PHP):** empty `app/Http/Controllers/ActiveVisitorController.php` and empty
`app/Http/Controllers/Admin/POSController.php`; orphaned methods flagged in the backend audit.

---

## 6. What to trust vs. re-verify
- **Trust:** COD order flow, storefront browsing/detail/cart, admin catalog/customers/dashboard, reviews, wishlist, chat — exercised or read end-to-end.
- **Re-verify at runtime:** Scout search (driver config), SMS recovery, FCM push (live keys/endpoint), UddoktaPay once handlers are (re)implemented — the single highest-value area to rebuild and re-review.
