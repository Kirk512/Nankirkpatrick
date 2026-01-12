# Next PR-sized Tasks

Each task below is scoped to stay under ~300 lines of change. Acceptance criteria and required tests are listed for each task.

## Completed Tasks ✅

1. ~~**Add Apply Online page template + CTA wiring**~~ ✅
   - CTAs throughout site link to https://www.applywithnan.com
   - Header includes Apply CTA button
   - All templates include Apply Online links

2. ~~**Build Blog landing page template**~~ ✅
   - Added `home.html` (blog index), `archive.html`, `single.html`
   - Posts display with featured image, date, excerpt, pagination

3. ~~**Ensure disclosures footer pattern is used site-wide**~~ ✅
   - Footer template includes NMLS IDs and Equal Housing Lender
   - All templates include header/footer parts

4. ~~**Add disclosure page references to footer links**~~ ✅
   - Footer links point to `/disclosures/`, `/privacy-policy/`, `/texas-recovery-fund/`

## Remaining Tasks

5. ~~**Add Events landing page layout**~~ ✅
   - Created `page-events.html` with intro content, recent events grid, CTA

6. ~~**Add Closings landing page layout + disclaimer placement**~~ ✅
   - Created `page-closings.html` with intro, recent closings grid, disclaimer shortcode, CTA
   - Closing disclaimer already on single closing template

7. **Wire global navigation links**
   - **Scope**: Update header template or navigation block to include core pages and disclosures.
   - **Acceptance criteria**:
     - Navigation includes Home, About, Apply Online, Reviews, Events, Closings, Blog, Contact, Disclosures.
     - Links are accessible and keyboard navigable.
   - **Tests**:
     - `scripts/verify.sh`
   - **Note**: Navigation menus are registered in `functions.php`. This task requires WordPress admin setup to create and assign menus.

8. **Document reviews admin workflow**
   - **Scope**: Expand `docs/REVIEWS_SETUP.md` with troubleshooting for cron/credentials.
   - **Acceptance criteria**:
     - Troubleshooting section includes common errors and remediation.
     - Documentation matches plugin behavior.
   - **Tests**:
     - `scripts/verify.sh`

9. **Add accessibility pass to templates**
   - **Scope**: Review templates for headings/alt text; fix missing alt text in theme assets.
   - **Acceptance criteria**:
     - All images in templates include alt text.
     - Heading hierarchy is logical on each page.
   - **Tests**:
     - `scripts/verify.sh`

10. ~~**Create a launch readiness checklist**~~ ✅
    - Created `docs/LAUNCH_CHECKLIST.md` with comprehensive checklist
    - Covers: theme activation, navigation, compliance, reviews, content, accessibility, performance, SEO, security

## WordPress Admin Tasks (Post-Activation)

These tasks require WordPress admin access after theme activation:

- [ ] Activate nk-theme in Appearance → Themes
- [ ] Create pages: Home, About, Reviews, Contact, Disclosures, Privacy Policy, Texas Recovery Fund
- [ ] Set Home as front page, Blog as posts page in Settings → Reading
- [ ] Create and assign Primary navigation menu
- [ ] Create and assign Footer navigation menu
- [ ] Create and assign Legal navigation menu
- [ ] Configure NMLS IDs in Customize → NK Theme Compliance Settings
- [ ] Add Google OAuth credentials in Settings → NK Reviews
- [ ] Run manual reviews sync to populate cache
