# Eureka — Next Tasks (prioritized)

_Derived from the 2026-07-05 full review. See `docs/PROJECT_STATUS.md` for evidence and
`docs/CODE_REVIEW.md` for security detail. Ordered by impact; check off as completed._

Priorities: **P0** = broken/exploitable, blocks core use · **P1** = security · **P2** = correctness
gaps · **P3** = cruft removal & polish.

---

## P0 — Broken / must fix

- [ ] **UddoktaPay online payment: implement confirmation flow.** `webhook` / `success` / `success2`
  / `fail` are referenced by `api.php:16` and `web.php:57,58,266,267` but don't exist → 500 on
  callback. Implement with **signature verification + paid-amount-vs-order-total reconciliation**, and
  set `pay_staus` on success. *(Or, if online payment is out of scope now, remove the routes + payment
  CTAs so users can't reach a dead flow.)*
- [ ] **Vendor withdrawal — negative amount inflation.** `WithdrawController::create` has no
  validation (`:20-52`). Add `amount => required|numeric|min:1`, re-check `amount <= vendor->amount`,
  wrap decrement + `Withdraw::create` in a `DB::transaction` with `lockForUpdate()`. Fix `cancel()`
  refund ledger (`wallate` vs `vendor_accounts.amount`).
- [ ] **Vendor product edit corrupts data.** `Vendor/ProductController::update` swaps attribute
  `qnty`↔`price` vs `store` (`:503-511`). Align the field mapping.
- [x] **Classified-ads public listing always empty.** `adsController::all()` filtered impossible
  `status='12'`; changed to `status=1` to match the approved-ad convention (`HomeController:71`). ✅
  *(Note: the admin approve flow that promotes ads 0→1 is still missing — `ClassicController` is
  referenced by `routes/admin.php:162-164` but the file doesn't exist. Tracked under P2.)*
- [ ] **Dead routes 500 on hit.** Add the missing methods or remove the routes: `login/vendor`
  (`HomeController::vendorLogin`), vendor `deleteImage` / `pre` / `partials` / `partialStatus` /
  `delete` (`routes/vendor.php:17,50,54,55,59`).
- [ ] **Empty POSController.** `Admin/POSController.php` is an empty file and its routes are
  commented. Either implement POS or delete the file + route stubs.

## P1 — Security (open findings)

- [x] **`dynamic_price` cart tampering.** ✅ Added `App\Services\ProductPriceCalculator` as the single
  pricing source of truth (base + attribute + colour). `OrderController@buyProduct` now recomputes the
  unit price server-side and no longer reads `request->dynamic_price`; `ProductController@getAttrPrice`
  uses the same service so the previewed price always equals the charged price. Verified: tampered
  `dynamic_price` is ignored, colour/attribute surcharges apply correctly, live endpoint returns the
  right price. *(Bonus: buy-now previously dropped attribute/colour surcharges — now fixed.)*
- [x] **IDOR sweep.** ✅ Wishlist delete + classified-ads delete/edit/update now scope to the owner
  (`where('user_id', auth()->id())->firstOrFail()`). Blog status/delete/update now use an
  admin-or-owner guard (`authorizeBlog()`) — admins keep moderation (routes are shared with the admin
  group) while account users are limited to their own. Verified: cross-user access → 404/403,
  owner/admin access allowed.
- [ ] **Refund / partial-payment idempotency.** Guard against replay in `Admin/Ecommerce/
  OrderController` refund (`:673` undefined `$id`, `:688` `refund_two`) and `partialStatus` (`:531`).
- [ ] **Rotate + relocate hardcoded secrets.** FCM key (HomeController:184, OrderController:590,
  Vendor/ProductController:829) and BDCourier key (OrderController:749). Move to config/env, rotate.
- [ ] **XSS on rich-text sinks.** Sanitize vendor/account HTML on input (HTML purifier) or render with
  `{{ }}`; 63 `{!! !!}` sinks across 17 storefront views (worst: checkout partials, product/search).
- [ ] **Committed secrets in git history.** Scrub `bootstrap/cache/config.php` (DB password, APP_KEY)
  with filter-repo/BFG, rotate credentials, restore `bootstrap/cache/.gitignore`.

## P2 — Correctness gaps

- [ ] **Multi-vendor split is inert** — `multi_order` never populated at checkout, so per-vendor
  commission / sub-status math runs on 0 rows. Decide: populate it (with care re: commission
  double-counting) or formally drop multi-vendor for now.
- [ ] **Admin login redirect** — undefined `$this->redirectAdmin` lands admin on `/`.
- [ ] **Settings `update()` type 1 persists nothing** (`:36-64`).
- [ ] **Staff edit is a dead end** — no update handler/route.
- [ ] **Blog `descripiton` typo** (`:54,122,152`) nulls descriptions; blog delete path missing `/`.
- [ ] **Courier `sendsteedfast`** — `return back()` before success check; add try/catch around Guzzle.
- [x] **Remove active debug (non-vendor):** removed `dd($user)` from `LoginController`; deleted the
  empty dead `app/Http/Controllers/ActiveVisitorController.php`. ✅ *(`echo $attribute;` in
  Vendor/ProductController left for the vendor pass.)*

## P3 — Cruft removal & visual polish

- [x] **`vite.config.js`** — dropped unused `bunny('Instrument Sans')` remote font (unblocks build). ✅
- [x] **Tier 1 dead files.** ✅ Deleted 45 verified-zero-reference `public/assets/plugins/` folders
  (`public/assets/plugins/` 57 MB → 29 MB) plus 3 dead files (`checkout.blade.php.backup-debug`,
  `main_old.js`, `unministy.css`). **Verification-driven, not audit-driven:** every folder/file was
  grep-checked for references first — this caught several the audit wrongly called dead (`jquery-ui.js`,
  `city.js` = checkout city dropdown, `flexslider.css`, `image-zoom.js` are all still in use and were
  **kept**). Post-delete checks: 0 blade references to any removed folder, 0 asset 404s on live pages.
  Kept (referenced): bootstrap, datatables(+bs4/buttons/responsive), dropify, dropzone, file-uploader,
  fontawesome-free, jquery, jszip, moment, pdfmake, select2(+theme), summernote.
- [ ] **Tier 2 remote CDN deps:** drop FA Pro kit (FA5 already loaded), consolidate per-view Google
  Font imports, self-host or remove boxicons/Material Icons/Google Translate.
- [ ] **Add `?.` optional chaining** to csrf-token lookups in `product-grid-view.blade.php:365` and
  `product-list-view.blade.php:227` (recurring null `getAttribute` JS error).
- [ ] **Wrap legacy account tables** (compare, wishlist, order, cashout, download, myrefer, payform,
  returns_order, ticket, ads/list) in an `overflow-x-auto` container for mobile.
- [ ] **Tier 3 (last):** remove the Bootstrap/jQuery/slick/toast base from the storefront after
  porting `main.js`, sliders, and `$.toast` to the Tailwind/Alpine stack.

---

## Suggested sequencing to discuss

1. **Stabilise (P0):** payment flow decision, withdrawal fix, product-edit fix, dead routes/ads/POS.
2. **Lock down (P1):** dynamic_price + IDOR sweep + secret rotation.
3. **Clean house (P3 Tier 1):** delete dead files + unused plugin folders — big, low-risk win.
4. **Polish (P2 + P3 Tier 2/3):** correctness gaps, then remaining cruft and responsive tables.

**Open questions for you:**
- Is **online payment (UddoktaPay)** in scope now, or should we remove the dead payment routes/CTAs
  until later?
- Is **multi-vendor** a real requirement, or is this effectively a single-brand ("Anas Luxyworld")
  store where we can drop the vendor split and simplify?
- OK to **delete the ~50 unused plugin folders** in one sweep, or do you want them removed
  incrementally with a check after each?
