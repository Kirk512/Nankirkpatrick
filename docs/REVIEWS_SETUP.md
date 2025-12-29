# Google Business Profile Reviews Sync Setup

This plugin syncs Google Business Profile reviews into the cached data stored in `nk_reviews_cache`.
The frontend always renders from this cache, and sync failures keep the previous cached data.

## Google setup

1. **Enable the API**
   - In Google Cloud Console, enable **Google Business Profile API** for the project you will use.
2. **Create OAuth credentials**
   - Create an OAuth **Web application** client ID.
   - Add the WordPress admin URL as an authorized redirect URI (for example: `https://example.com/wp-admin/`).
3. **Generate a refresh token**
   - Use the OAuth consent flow to authorize the `https://www.googleapis.com/auth/business.manage` scope.
   - Exchange the authorization code for a refresh token (store it securely).
4. **Find the account and location IDs**
   - Use the Business Profile API or the GBP interface to identify the `accountId` and `locationId` that own the reviews.

## WordPress settings

1. Go to **Settings → NK Reviews**.
2. Enter the **Account ID**, **Location ID**, **OAuth Client ID**, **OAuth Client Secret**, and **OAuth Refresh Token**.
3. Click **Save Settings**.
4. Click **Sync Now** to pull reviews immediately, or wait for the daily WP-Cron run.

## Manual sanity check (no test framework)

1. Save valid credentials and location IDs.
2. Click **Sync Now** and confirm:
   - The success notice appears.
   - `Last updated` timestamp changes.
   - The frontend shortcode renders the new cached reviews.

