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
if rg -n \
  -e "AIza" \
  -e "AKIA" \
  -e "-----BEGIN" \
  -e "client_secret\\s*[:=]\\s*[\"'][^\"']{10,}[\"']" \
  -e "refresh_token\\s*[:=]\\s*[\"'][^\"']{10,}[\"']" \
  --glob "!scripts/verify.sh"; then
  printf "\nSecret marker(s) detected. Remove secrets before proceeding.\n" >&2
  exit 1
fi

printf "\nVerify checks passed.\n"
