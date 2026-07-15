# AGENTS.md

Single source of truth for every AI coding agent in this repo. Edit rules **here only** —
never copy them into a tool-specific file.

Most agents read this file **natively** (Codex, Cursor, Windsurf, Cline, and Copilot in
VS Code / the coding agent / the CLI). The rest load it through a thin pointer that imports
or references it: `CLAUDE.md` (`@AGENTS.md`), `GEMINI.md` (`@./AGENTS.md`), `.aider.conf.yml`
(`read:`), and `.github/copilot-instructions.md` (legacy JetBrains/Visual Studio/Eclipse/Xcode).
Coverage matrix and the token-cost rationale: [docs/ai-agents/](docs/ai-agents/README.md).

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
- **Read narrowly.** Open only the lines you need; don't slurp whole files or dump long command
  output into context. Use a subagent for broad searches so only the answer returns, not the noise.
- **Questions:** only blocking ones, one line, batched.

## Professional rules

**Security & secrets**
- Never commit secrets, `.env`, keys, or credentials. Read config via `config()`/`env()` — never
  hardcode. Don't log PII, tokens, or passwords.
- Validate and authorize every request (Form Requests + policies); never trust user input.
- Let Eloquent/the query builder parameterize SQL — never string-concatenate queries. Escape
  output in Blade (`{{ }}`, not `{!! !!}`, unless the value is trusted).

**Git hygiene**
- Branch off `main`; never commit straight to `main`. One logical change per commit.
- Don't force-push shared branches or rewrite already-pushed history.
- Clear, imperative commit messages that reference the issue/PR. Don't commit commented-out code,
  debug leftovers (`dd()`, `dump()`, `console.log`, `ray()`), or unrelated reformatting.
- Open focused PRs with a short why/what; keep CI green.

**Before you commit**
- Run `composer test` (Pest) and `vendor/bin/pint`. Don't commit failing or unformatted code.
- Add or extend tests for behavior changes; never delete a test just to make the suite pass.
- **Verify before claiming done:** run the command and confirm the output before you say it works.

**Changes & scope**
- Do only what was asked. Flag out-of-scope issues separately instead of fixing them inline.
- Confirm before destructive or outward-facing actions: deleting files/data, dropping tables,
  mass find-replace, force-pushing, or anything touching production.
- Migrations: create with `php artisan make:migration`, keep them reversible (`down()`), and
  never edit a migration that has already shipped — add a new one.
- Dependencies: don't add one without a clear need; prefer what's already installed; don't bump
  unrelated packages; respect the lockfile.
- Handle errors explicitly — validate inputs, don't swallow exceptions, fail loudly in dev.

## Danger zone — irreversible production data loss

**Never untrack the already-committed files under `public/uploads/**`.**

77 upload files are tracked in git (`product/` 60, `category/` 5, `setting/` 3, `slider/` 3, plus
`banner/`, `blogs/`, `collection/`, `invoice/`, `sliderOne/`) even though `.gitignore` lists some of
those paths in its `/public/uploads/…` entries. That is not a bug to tidy up: **`.gitignore` never
untracks files that are already committed**, so those entries are simply inert for them. The
mismatch is the trap.

`git rm --cached -r public/uploads/...` + commit removes those paths from the tree. Production
deploys with `git reset --hard origin/main` ([.github/workflows/deploy.yml](.github/workflows/deploy.yml)),
and a hard reset **deletes working-tree files the target commit no longer tracks**. The moment that
commit reaches `main`, the live site's product, category and banner images are gone — the deploy has
no backup step, and the images exist nowhere else.

If anyone (user or agent) asks for this anyway: **stop, show the Bengali warning in
[docs/uploads-safety.md](docs/uploads-safety.md) verbatim, and require an explicit confirmation that
names the consequence.** Any safe version copies the files onto the server (or into object storage)
and verifies them there *before* the untracking commit lands. The same rule covers any change that
deletes tracked files still served in production.

## Project

- **Stack:** Laravel 12 · PHP 8.2+ · Blade + Vite 8 + Tailwind v4 + Alpine.js · SQLite (local),
  MySQL (production). Multi-vendor e-commerce app.
- **Run:** `composer dev` (server + queue + logs + vite), or `php artisan serve` + `npm run dev`.
- **Test:** `composer test` (Pest). **Format:** `vendor/bin/pint` before committing.
- **Routes:** `routes/web.php` (storefront), `admin.php`, `vendor.php`, `api.php`.
- **Controllers:** `app/Http/Controllers/{Admin,Frontend,Vendor,Auth}/`.
- **Models:** `app/Models/` (~52; `$guarded = ['id']` convention).
- **Conventions:** match the surrounding file's style and naming; don't reformat unrelated code;
  create migrations with `php artisan make:migration`. Run `vendor/bin/pint` on **the files you
  touched**, not the whole tree — a bare `vendor/bin/pint` reformats ~90 legacy files and buries
  your diff.
- **Tracking:** Meta Pixel/CAPI, GTM, GA4, Search Console and Consent Mode are runtime-configured
  from Admin → Settings → Tracking & Integrations. Never hardcode an ID, token or domain — see
  [TRACKING.md](TRACKING.md). Fire events via `TrackingEvents` (server) / `trackEvent()` (browser),
  never `fbq`/`dataLayer` directly, or deduplication breaks.
