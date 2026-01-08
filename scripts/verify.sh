#!/usr/bin/env bash
set -euo pipefail

php_paths=(
  "wp-content/plugins/nk-core"
  "wp-content/plugins/nk-reviews"
)

printf "Running php -l on plugin PHP files...\n"
find "${php_paths[@]}" -type f -name "*.php" -print0 \
  | xargs -0 -r -n1 php -l

printf "Scanning for obvious secret markers...\n"
if rg -n -e "AIza" -e "AKIA" -e "-----BEGIN" -e "client_secret" -e "refresh_token" \
  --glob "!scripts/verify.sh" \
  --glob "!wp-content/plugins/nk-reviews/includes/admin-settings.php" \
  --glob "!wp-content/plugins/nk-reviews/includes/sync.php" \
  --glob "!wp-content/plugins/nk-reviews/nk-reviews.php"; then
  printf "\nSecret marker(s) detected. Remove secrets before proceeding.\n" >&2
  exit 1
fi

printf "\nVerify checks passed.\n"
