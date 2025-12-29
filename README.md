# Nankirkpatrick.com

WordPress codebase for nankirkpatrick.com, a Nan-focused professional site hosted on GoDaddy Managed WordPress.

## Local development

### Prerequisites
- PHP 8.x
- Composer (as needed by local tooling)
- Node.js (as needed by local tooling)
- Optional: Docker / Docker Compose
- A local WordPress install (core + database)

### WordPress version assumptions
- Site runs on a recent, supported WordPress 6.x release. If your local environment differs, align it before making changes.

### Getting started (example)
1. Configure a local WordPress instance.
2. Point the local site to this repository for themes/plugins as appropriate.
3. Add any required environment values locally (never commit secrets).

## Linting and tests
Tooling will be added later. For now:
- Run `php -l` on any modified PHP files.
- If additional linters/tests are added, document and run them here.

## Deployment model
The site is hosted on GoDaddy Managed WordPress.
- Deployment may be manual (dashboard upload or SFTP) or Git-based if enabled.
- Do not assume CI/CD is available.
- Coordinate releases with the site owner and verify disclosures before deploying.
