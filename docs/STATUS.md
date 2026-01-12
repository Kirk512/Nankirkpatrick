# Repo Status Audit

This status report summarizes completed work, gaps, risks/compliance concerns, and the next steps for delivering the full Nan Kirkpatrick site build.

## Roadmap phases (0–4)

0. **Foundation & guardrails** (repo structure, verification scripts, PR workflow, secret scanning).
1. **Core data model & integrations** (CPTs/taxonomies, reviews sync, cron/caching).
2. **Theme scaffolding** (block theme templates, template parts, base styles).
3. **Content build-out** (page content, block patterns wired into templates, navigation).
4. **Launch & operations** (QA, compliance validation, performance, monitoring).

## Completed items (with references)

### Guardrails
- Verification script runs `php -l` against both plugins and scans for secrets using value-oriented patterns without excluding plugin files (only `scripts/verify.sh` is excluded). (`scripts/verify.sh`) 
- PR template exists and includes Summary, Rationale, Testing, Screenshots, Risk/Rollback, Compliance impact, and Next steps. (`.github/PULL_REQUEST_TEMPLATE.md`)

### nk-reviews
- Admin settings do not echo secrets; secret inputs render with empty values and “leave blank” copy. (`wp-content/plugins/nk-reviews/includes/admin-settings.php::nk_reviews_render_settings_page`)
- Secret fields honor “leave blank to keep existing” behavior. (`wp-content/plugins/nk-reviews/includes/admin-settings.php::nk_reviews_handle_save_settings`)
- Cache and secret options are stored with autoload disabled, including first creation. (`wp-content/plugins/nk-reviews/includes/sync.php::nk_reviews_update_option_noautoload`, `nk_reviews_disable_autoload_for_options`)
- Cron cadence is weekly with a 3-day safety window to avoid unnecessary API calls. (`wp-content/plugins/nk-reviews/includes/sync.php::nk_reviews_add_cron_schedules`, `nk_reviews_handle_cron_sync`)
- Shortcode shows cached vs. showing counts, defaults to “mentions Nan” with configurable keyword, and has a toggle to show all cached reviews. Output is escaped and the client-side toggle uses `textContent`. (`wp-content/plugins/nk-reviews/includes/shortcode.php::nk_reviews_render_shortcode`)

### nk-core
- CPTs and taxonomies exist with distinct slugs (`event_year`, `closing_year`). (`wp-content/plugins/nk-core/includes/class-nk-core.php::register_event_taxonomy`, `register_closing_taxonomy`)
- Block patterns exist for the homepage hero, reviews, CTA strip, and disclosures footer; CTA buttons point to applywithnan.com. (`wp-content/plugins/nk-core/includes/class-nk-core.php::register_block_patterns`)
- NMLS/Equal Housing Lender requirements are represented in the blueprint and in the disclosures pattern. (`docs/BLUEPRINT.md`, `wp-content/plugins/nk-core/includes/class-nk-core.php::register_block_patterns`)

### Theme scaffolding
- Core templates exist for home, about, contact, reviews, disclosures, events/closings archives, and single CPTs. (`wp-content/themes/nk-theme/templates/`)
- Disclosures page includes required NMLS identifiers and Equal Housing Lender messaging. (`wp-content/themes/nk-theme/templates/page-disclosures.html`)

### Docs alignment
- Blueprint requirements match implemented CPTs, disclosures, and reviews behavior. (`docs/BLUEPRINT.md`, `wp-content/plugins/nk-core/includes/class-nk-core.php`, `wp-content/plugins/nk-reviews/includes/shortcode.php`)
- Reviews setup doc reflects weekly cron cadence and cache behavior. (`docs/REVIEWS_SETUP.md`, `wp-content/plugins/nk-reviews/includes/sync.php`)
- Code review checklist aligns with current capability checks, escaping strategy, and secret-handling patterns. (`docs/CODE_REVIEW.md`)

## Remaining items (checklist)

### Foundation & guardrails
- [ ] Confirm PR template is enforced in workflow tooling (e.g., required checks on CI).

### Content build-out
- [ ] Build Apply Online page/template and ensure CTA entry points go to https://www.applywithnan.com.
- [ ] Build Blog landing page and post templates (beyond `index.html`).
- [ ] Build Events/Closings landing page content and navigation wiring.
- [ ] Build Reviews page content using the reviews shortcode pattern.
- [ ] Assemble global navigation with the required pages and disclosure links.

### Compliance & disclosures
- [ ] Confirm disclosures/footer components are used site-wide (header/footer templates).
- [ ] Confirm closing disclaimer appears on closing templates and/or in layouts.

### QA & launch
- [ ] Run a pre-launch checklist for accessibility, performance, and compliance.

## Risks / compliance concerns

- **Disclosure visibility risk**: NMLS/Equal Housing Lender copy exists in patterns and the disclosures page, but ensuring it’s in the live header/footer templates is still required for site-wide visibility.
- **Content completion risk**: Required pages (Apply Online, Blog) are not fully built or templated, which may delay launch readiness.
- **Operational risk**: Reviews sync depends on OAuth secrets and WP-Cron; schedule drift or missing credentials would stop updates.

## Next steps (PR-sized tasks)

See `docs/TODO.md` for the next 10 scoped tasks with acceptance criteria and tests.
