# Launch Readiness Checklist

Pre-launch verification checklist for the Nan Kirkpatrick mortgage website.

## 1. Theme Activation & Setup

### WordPress Admin
- [ ] Activate **nk-core** plugin in Plugins → Installed Plugins
- [ ] Activate **nk-reviews** plugin in Plugins → Installed Plugins  
- [ ] Activate **nk-theme** in Appearance → Themes

### Page Creation
Create the following pages and assign the correct template:

| Page | Slug | Template |
|------|------|----------|
| Home | `home` | Page: Home |
| About | `about` | Page: About |
| Reviews | `reviews` | Page: Reviews |
| Contact | `contact` | Page: Contact |
| Disclosures | `disclosures` | Page: Disclosures |
| Privacy Policy | `privacy-policy` | Page: Privacy Policy |
| Texas Recovery Fund | `texas-recovery-fund` | Page: Texas Recovery Fund |
| Events | `events-landing` | Page: Events |
| Closings | `closings-landing` | Page: Closings |
| Blog | `blog` | Default (uses home.html) |

### Reading Settings
- [ ] Settings → Reading → Your homepage displays: **A static page**
- [ ] Homepage: **Home**
- [ ] Posts page: **Blog**

### Permalinks
- [ ] Settings → Permalinks → Post name (`/%postname%/`)
- [ ] Save changes to flush rewrite rules

## 2. Navigation Setup

### Create Menus (Appearance → Menus)

**Primary Navigation:**
- [ ] Create menu named "Primary Navigation"
- [ ] Add: Home, About, Reviews, Events, Closings, Blog, Contact
- [ ] Assign to "Primary Navigation" location

**Footer Navigation:**
- [ ] Create menu named "Footer Navigation"  
- [ ] Add: Home, About, Reviews, Contact
- [ ] Assign to "Footer Navigation" location

**Legal Navigation:**
- [ ] Create menu named "Legal Navigation"
- [ ] Add: Disclosures, Privacy Policy, Texas Recovery Fund
- [ ] Assign to "Legal Navigation" location

## 3. Compliance Configuration

### Customizer Settings (Appearance → Customize → NK Theme Compliance Settings)
- [ ] Set **Nan's NMLS ID**: (e.g., 123456)
- [ ] Set **Company NMLS ID**: (e.g., 789012)
- [ ] Set **Company Name**: (e.g., ABC Mortgage Company)
- [ ] Set **Phone Number**: (e.g., (512) 555-1234)
- [ ] Set **Email Address**: (e.g., nan@example.com)

### Verify Disclosures Display
- [ ] NMLS ID visible in header
- [ ] NMLS IDs visible in footer
- [ ] Equal Housing Lender logo visible in footer
- [ ] Disclosures page accessible and complete
- [ ] Privacy Policy page accessible and complete
- [ ] Texas Recovery Fund page accessible and complete
- [ ] Closing disclaimer appears on single closing posts

## 4. Reviews Plugin Setup

### Configure Reviews (Settings → NK Reviews)
- [ ] Enter Google OAuth Client ID
- [ ] Enter Google OAuth Client Secret
- [ ] Enter Google Place ID
- [ ] Save settings (credentials stored securely)

### Test Reviews Sync
- [ ] Click "Sync Now" to perform manual sync
- [ ] Verify reviews appear on Reviews page
- [ ] Verify "Only reviews mentioning Nan" toggle works
- [ ] Check that weekly cron is scheduled (Tools → Scheduled Actions or similar)

## 5. Content Verification

### All Pages Load Correctly
- [ ] Home page loads with hero, services, reviews, CTA
- [ ] About page loads with bio content
- [ ] Reviews page loads with reviews shortcode
- [ ] Contact page loads with contact information
- [ ] Disclosures page loads with all required disclosures
- [ ] Privacy Policy page loads
- [ ] Texas Recovery Fund page loads
- [ ] Events landing page loads
- [ ] Closings landing page loads
- [ ] Blog page loads with post list (if posts exist)

### CPT Archives Work
- [ ] `/events/` archive page loads
- [ ] `/closings/` archive page loads
- [ ] Single event post loads
- [ ] Single closing post loads (with disclaimer)

### Error Pages
- [ ] 404 page displays correctly (visit `/nonexistent-page/`)
- [ ] Search results page works

## 6. Accessibility Audit

### Keyboard Navigation
- [ ] All navigation links accessible via Tab key
- [ ] Focus states visible on interactive elements
- [ ] Skip-to-content link works (if present)
- [ ] Dropdown/mobile menus keyboard accessible

### Screen Reader Compatibility
- [ ] Heading hierarchy is logical (h1 → h2 → h3)
- [ ] All images have alt text
- [ ] Form fields have labels
- [ ] Links have descriptive text (not just "click here")

### Color Contrast
- [ ] Text meets WCAG AA contrast ratio (4.5:1 for body text)
- [ ] Links are distinguishable from surrounding text

## 7. Performance Check

### Page Speed
- [ ] Run Google PageSpeed Insights on home page
- [ ] Target: 90+ on mobile, 95+ on desktop
- [ ] Images are appropriately sized
- [ ] No render-blocking resources (or minimized)

### Caching
- [ ] Caching plugin installed and configured (if applicable)
- [ ] Static assets cached appropriately

### Image Optimization
- [ ] All images compressed
- [ ] Modern formats used where possible (WebP)
- [ ] Lazy loading enabled for below-fold images

## 8. SEO Basics

### Meta Tags
- [ ] Each page has unique title
- [ ] Each page has meta description
- [ ] Open Graph tags present for social sharing

### Sitemap
- [ ] XML sitemap generated (`/sitemap.xml`)
- [ ] Sitemap submitted to Google Search Console

### Robots.txt
- [ ] robots.txt allows search engine crawling
- [ ] No important pages accidentally blocked

## 9. Security

### Basic Security
- [ ] WordPress, plugins, and themes updated
- [ ] Strong admin passwords used
- [ ] Login attempts limited (security plugin)
- [ ] SSL certificate active (HTTPS)

### Secrets Verification
- [ ] Run `scripts/verify.sh` - no secrets detected
- [ ] OAuth credentials stored in database, not files
- [ ] No API keys committed to repository

## 10. Final Verification

### Cross-Browser Testing
- [ ] Chrome (desktop & mobile)
- [ ] Firefox
- [ ] Safari (desktop & mobile)
- [ ] Edge

### Mobile Responsiveness
- [ ] Home page responsive on mobile
- [ ] Navigation works on mobile (hamburger menu)
- [ ] Forms usable on mobile
- [ ] Text readable without zooming

### Forms & CTAs
- [ ] "Apply Online" buttons link to https://www.applywithnan.com
- [ ] Contact form works (if applicable)
- [ ] All email links work (`mailto:`)
- [ ] All phone links work (`tel:`)

---

## Sign-Off

| Check | Completed By | Date |
|-------|--------------|------|
| Theme & Plugin Activation | | |
| Navigation Setup | | |
| Compliance Verification | | |
| Reviews Setup | | |
| Content Audit | | |
| Accessibility Audit | | |
| Performance Check | | |
| SEO Basics | | |
| Security Review | | |
| Final Verification | | |

**Site is ready for launch:** ☐ Yes ☐ No

**Notes:**
