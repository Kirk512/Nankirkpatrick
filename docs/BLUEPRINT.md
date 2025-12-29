# Site Blueprint

## Primary goal
Build and maintain a Nan-focused professional site that drives conversions to the CTA **Apply Online** linking to:
- https://www.applywithnan.com (redirects to Arive POS)

## Required disclosures and identifiers
These elements must be prominent and available site-wide:
- **Nan Kirkpatrick #212026** must be displayed prominently across the site.
- **Company NMLS ID: 218131** must be visible.
- **Equal Housing Lender** logo must be displayed.
- Disclosures content must be **easily accessible** (not necessarily on a single page).

## Pages and content model
Required pages and primary content areas:
- Home
- About
- Apply Online (CTA)
- Reviews
- Events (lookbook by year)
- Closings (compliance-safe)
- Blog
- Contact
- Disclosures hub

Content types:
- **Events**: Custom Post Type (CPT) with taxonomy **event_year**
- **Closings**: CPT with taxonomy **closing_year**

## Google Reviews
- Pull reviews from the **Google Business Profile API**.
- Cache responses in WordPress and render from cache.
- Provide a UI filter toggle **“Mentions Nan”**.
  - The toggle should not hide reviews by default.

## Compliance notes
- Avoid MLS photo/price usage unless rights are explicitly confirmed.
- Closings must **not** imply Nan is a realtor.
  - Include a clear disclaimer on closings pages.

## Navigation and footer
- Global navigation must include the core pages listed above.
- Global footer requirements:
  - NMLS identifiers
  - Disclosure links
  - Equal Housing Lender logo
