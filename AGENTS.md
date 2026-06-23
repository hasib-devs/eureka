# AGENTS.md

Single source of truth for every AI coding agent in this repo. `CLAUDE.md`, `GEMINI.md`,
Copilot, and Cursor all point here — edit rules in this file only.

## Output rules (token discipline — top priority)

Agents waste tokens narrating instead of acting. Keep messages minimal:

- **Act first, explain only if asked.** No preamble ("I'll now…", "Sure!", "Great question")
  and no postamble ("Let me know if…", "Hope this helps").
- **Don't narrate or recap.** Never announce what you're about to do, and never restate a
  diff, file, or command output the tool already showed.
- **Default to ≤4 lines of prose.** A one-word or one-line answer is correct when it suffices.
  Expand only when asked for detail or when a decision is non-obvious.
- **Prefer tools over talk.** Make the edit / run the command. Don't paste large code blocks
  into the message when you can edit the file.
- **No completion summaries** — no "what I changed" tables or next-step lists unless requested.
- **Reference, don't reproduce.** Cite `path/file.php:42`; don't quote the code back.
- **Questions:** only blocking ones, one line, batched.

## Project

- **Stack:** Laravel 11 · PHP 8.2+ · Blade + Vite 8 + Tailwind v4 + Alpine.js · SQLite (local).
  Multi-vendor e-commerce app.
- **Run:** `composer dev` (server + queue + logs + vite), or `php artisan serve` + `npm run dev`.
- **Test:** `composer test` (Pest). **Format:** `vendor/bin/pint` before committing.
- **Routes:** `routes/web.php` (storefront), `admin.php`, `vendor.php`, `api.php`.
- **Controllers:** `app/Http/Controllers/{Admin,Frontend,Vendor,Auth}/`.
- **Models:** `app/Models/` (~52; `$guarded = ['id']` convention).
- **Conventions:** match the surrounding file's style and naming; don't reformat unrelated code;
  create migrations with `php artisan make:migration`.
