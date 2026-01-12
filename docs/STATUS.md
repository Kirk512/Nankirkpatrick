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
- Complete FSE child theme with design system (CSS custom properties, color palette, typography). (`wp-content/themes/nk-theme/style.css`, `theme.json`)
- Core templates exist for home, about, contact, reviews, disclosures, events/closings archives, and single CPTs. (`wp-content/themes/nk-theme/templates/`)
- Blog/archive support with `home.html`, `archive.html`, `single.html` templates. (`wp-content/themes/nk-theme/templates/`)
- 404 and search result pages created. (`wp-content/themes/nk-theme/templates/404.html`, `search.html`)
- Generic page fallback template. (`wp-content/themes/nk-theme/templates/page.html`)
- Disclosures page includes required NMLS identifiers and Equal Housing Lender messaging. (`wp-content/themes/nk-theme/templates/page-disclosures.html`)
- Header template with sticky nav, NMLS display, and Apply CTA. (`wp-content/themes/nk-theme/parts/header.html`)
- Footer template with 3-column layout, compliance bar (NMLS, Equal Housing), and legal links. (`wp-content/themes/nk-theme/parts/footer.html`)
- Navigation menus registered (primary, footer, legal). (`wp-content/themes/nk-theme/functions.php`)
- Block patterns registered for page-hero, about-section, services-grid, contact-section, testimonial. (`wp-content/themes/nk-theme/functions.php`)
- Shortcodes for NMLS display and Equal Housing icon. (`wp-content/themes/nk-theme/functions.php`)
- Client-side JavaScript for reviews toggle, smooth scroll, header effects. (`wp-content/themes/nk-theme/assets/js/theme.js`)
- Equal Housing Lender SVG icon. (`wp-content/themes/nk-theme/assets/images/equal-housing-lender.svg`)

### Docs alignment
- Blueprint requirements match implemented CPTs, disclosures, and reviews behavior. (`docs/BLUEPRINT.md`, `wp-content/plugins/nk-core/includes/class-nk-core.php`, `wp-content/plugins/nk-reviews/includes/shortcode.php`)
- Reviews setup doc reflects weekly cron cadence and cache behavior. (`docs/REVIEWS_SETUP.md`, `wp-content/plugins/nk-reviews/includes/sync.php`)
- Code review checklist aligns with current capability checks, escaping strategy, and secret-handling patterns. (`docs/CODE_REVIEW.md`)

## Remaining items (checklist)

### Foundation & guardrails
- [ ] Confirm PR template is enforced in workflow tooling (e.g., required checks on CI).

### Content build-out
- [x] ~~Build Apply Online page/template and ensure CTA entry points go to https://www.applywithnan.com.~~ (CTAs wired throughout templates)
- [x] ~~Build Blog landing page and post templates (beyond `index.html`).~~ (`home.html`, `single.html`, `archive.html`)
- [x] ~~Build Events/Closings landing page content and navigation wiring.~~ (`page-events.html`, `page-closings.html`)
- [x] ~~Build Reviews page content using the reviews shortcode pattern.~~ (`page-reviews.html`)
- [ ] Assemble global navigation with the required pages and disclosure links (requires WP Admin).

### Compliance & disclosures
- [x] ~~Confirm disclosures/footer components are used site-wide (header/footer templates).~~ (Footer pattern with NMLS/Equal Housing in all templates)
- [x] ~~Confirm closing disclaimer appears on closing templates and/or in layouts.~~ (`single-closing.html`)

### QA & launch
- [ ] Run a pre-launch checklist for accessibility, performance, and compliance.

## Risks / compliance concerns

- **Disclosure visibility**: ✅ RESOLVED - NMLS/Equal Housing Lender copy now in header and footer template parts, visible site-wide.
- **Content completion**: ✅ MOSTLY RESOLVED - All core page templates built. Events/Closings landing pages and navigation wiring remain.
- **Operational risk**: Reviews sync depends on OAuth secrets and WP-Cron; schedule drift or missing credentials would stop updates.

## Next steps (PR-sized tasks)

See `docs/TODO.md` for the remaining scoped tasks with acceptance criteria and tests.
