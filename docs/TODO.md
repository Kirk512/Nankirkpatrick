# Next PR-sized Tasks

Each task below is scoped to stay under ~300 lines of change. Acceptance criteria and required tests are listed for each task.

1. **Add Apply Online page template + CTA wiring**
   - **Scope**: Add `page-apply-online.html` template and ensure CTA buttons link to https://www.applywithnan.com.
   - **Acceptance criteria**:
     - Apply Online page exists and renders with primary CTA.
     - All CTA buttons on the page use applywithnan.com.
     - No disclosure copy removed.
   - **Tests**:
     - `scripts/verify.sh`
     - `php -l wp-content/plugins/nk-core/includes/class-nk-core.php`

2. **Build Blog landing page template**
   - **Scope**: Add a dedicated `page-blog.html` or customize `index.html` for blog landing.
   - **Acceptance criteria**:
     - Blog page lists latest posts with heading and summary.
     - Accessible headings and link text are used.
   - **Tests**:
     - `scripts/verify.sh`

3. **Add Events landing page layout**
   - **Scope**: Add a page template that introduces Events and links to the Events archive.
   - **Acceptance criteria**:
     - Events landing page exists with CTA to events archive.
     - No changes to CPT slugs or taxonomies.
   - **Tests**:
     - `scripts/verify.sh`

4. **Add Closings landing page layout + disclaimer placement**
   - **Scope**: Create a Closings landing page and ensure closing disclaimers are visible on single closing pages (if not already).
   - **Acceptance criteria**:
     - Closing disclaimer appears on single closing layout.
     - Closing landing page contains compliance copy.
   - **Tests**:
     - `scripts/verify.sh`

5. **Wire global navigation links**
   - **Scope**: Update header template or navigation block to include core pages and disclosures.
   - **Acceptance criteria**:
     - Navigation includes Home, About, Apply Online, Reviews, Events, Closings, Blog, Contact, Disclosures.
     - Links are accessible and keyboard navigable.
   - **Tests**:
     - `scripts/verify.sh`

6. **Ensure disclosures footer pattern is used site-wide**
   - **Scope**: Insert the disclosures footer pattern into `footer.html` or global template part.
   - **Acceptance criteria**:
     - Footer shows Nan/Company NMLS and Equal Housing Lender content on all templates.
   - **Tests**:
     - `scripts/verify.sh`

7. **Document reviews admin workflow**
   - **Scope**: Expand `docs/REVIEWS_SETUP.md` with troubleshooting for cron/credentials.
   - **Acceptance criteria**:
     - Troubleshooting section includes common errors and remediation.
     - Documentation matches plugin behavior.
   - **Tests**:
     - `scripts/verify.sh`

8. **Add accessibility pass to templates**
   - **Scope**: Review templates for headings/alt text; fix missing alt text in theme assets.
   - **Acceptance criteria**:
     - All images in templates include alt text.
     - Heading hierarchy is logical on each page.
   - **Tests**:
     - `scripts/verify.sh`

9. **Add disclosure page references to footer links**
   - **Scope**: Replace placeholder disclosure links in patterns/templates with real URLs.
   - **Acceptance criteria**:
     - Footer links point to `/disclosures/`, `/privacy-policy/`, `/texas-recovery-fund/`.
   - **Tests**:
     - `scripts/verify.sh`

10. **Create a launch readiness checklist**
    - **Scope**: Add `docs/LAUNCH_CHECKLIST.md` with compliance, QA, and performance checks.
    - **Acceptance criteria**:
      - Checklist covers disclosures, CTA, reviews sync, and accessibility.
    - **Tests**:
      - `scripts/verify.sh`
