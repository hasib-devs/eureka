# GitHub Copilot instructions

Canonical rules for this repo live in **[AGENTS.md](../AGENTS.md)** — read and follow it.

VS Code chat, the Copilot coding agent, and the Copilot CLI read `AGENTS.md` automatically, so
this file is only load-bearing for the Copilot plugins that don't: JetBrains, Visual Studio,
Eclipse, and Xcode. (Copilot concatenates all instruction files rather than overriding, so this
stays minimal to avoid drifting out of sync with AGENTS.md.)

Core rules for those plugins: be terse — act through edits/commands, don't narrate or summarize
completed work, default to ≤4 lines of prose. Branch off `main`; run `composer test` and
`vendor/bin/pint` before committing; never commit secrets, `.env`, or debug leftovers.
