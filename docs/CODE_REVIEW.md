# Code Review Audit Notes

This document captures the post-fix code review checklist for the reviews plugin audit. It is intended to be a durable artifact that can be referenced during future maintenance and deployment reviews.

## Capability checks + nonce usage (admin-post handlers)
- Verify all `admin-post.php` handlers enforce capability checks (e.g., `current_user_can`) before processing requests.
- Confirm each handler uses a dedicated nonce action and name, and validates it with `check_admin_referer` or `wp_verify_nonce`.
- Ensure handlers are registered on the correct hook (`admin_post_...` or `admin_post_nopriv_...`) and that `nopriv` handlers are only used for truly public actions.

## Escaping strategy (output escaping vs sanitization on save)
- Sanitize and validate input on save using the narrowest possible sanitizer for the field type (e.g., text vs. URL).
- Escape output on render, using context-appropriate functions (`esc_html`, `esc_attr`, `esc_url`, `wp_kses_post`).
- Avoid relying solely on sanitization at save time; treat output escaping as the primary defense.

## Secret handling expectations
- Do not commit secrets to the repository.
- Ensure admin UI screens hide secrets (masking or reveal-on-demand) and avoid rendering secrets in HTML unless explicitly required.
- Verify secret scanning is enabled in the delivery pipeline and treat scanning failures as blockers.

## Cron cadence and caching behavior
- Confirm cron schedules are documented and match expected cadence (e.g., hourly/daily jobs).
- Ensure cron jobs are idempotent and resilient to missed schedules.
- Validate caching behavior: cache keys are stable, caches are invalidated on updates, and cache TTLs align with the data’s freshness requirements.

## What to check before deploy
- All admin-post handlers pass capability and nonce validation.
- Output escaping is applied in every render path.
- No secrets are stored in the repo or exposed in the UI.
- Cron cadence and cache behavior are verified against the documented expectations.
- `scripts/verify.sh` passes cleanly.
