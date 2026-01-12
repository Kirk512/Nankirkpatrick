# Contributor Rules (Codex + Humans)

These rules apply to all contributions in this repository.

## Workflow
- **Always open a PR**; never push directly to `main`.
- Each PR must be **small and focused** (max **300 lines changed** unless explicitly justified in the PR).

## Agent execution rules (anti-churn)
- **No command churn**: do not run multiple variants of failing commands hoping one works.
- **Fail fast**: if a command fails for environment reasons (PATH, permissions, quoting), stop within **2 attempts**, summarize the failure, and switch to a simpler diagnostic or ask for the missing detail.
- **Ask for known paths early**: on Windows, if a dependency is likely installed but not on PATH (e.g., XAMPP PHP), ask the user for the install path immediately instead of guessing repeatedly.
- **Prefer deterministic checks**: use `Test-Path`/`where`/`command -v` before invoking tools; avoid complex quoting that is brittle across PowerShell/Git Bash.
- **State assumptions**: before relying on a toolchain (bash/php/rg), explicitly state what is required and what was detected.

## PR requirements
Every PR must include:
- **Summary**
- **Rationale**
- **Screenshots** (if UI changes)
- **Testing**
- **Risk / rollback notes**

## Content and compliance
- **Never remove or alter disclosure copy** without an explicit callout in the PR.
- Nan Kirkpatrick and NMLS identifiers must remain present where required.

## Architecture and tooling
- Avoid heavy page builders and excessive plugin dependencies.
- Prefer **Gutenberg blocks** and keep plugin count minimal.

## Security
- **No secrets** in the repo.
- Sanitize outputs and escape HTML.

## Accessibility
- Use semantic headings.
- Provide alt text.
- Ensure keyboard navigation.
- Use ARIA only where needed.

## Performance
- Avoid heavy third-party scripts.
- Lazy-load images where appropriate.

## Testing
- At minimum, run `php -l` on changed PHP files.
- Add and use more tooling as it becomes available.
- Required local check: `scripts/verify.sh`.
