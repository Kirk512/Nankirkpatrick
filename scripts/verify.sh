#!/usr/bin/env bash
set -euo pipefail

php_paths=(
  "wp-content/plugins/nk-core"
  "wp-content/plugins/nk-reviews"
)

has_cmd() {
  command -v "$1" >/dev/null 2>&1
}

is_windows_bash() {
  case "$(uname -s 2>/dev/null || echo '')" in
    MINGW*|MSYS*|CYGWIN*) return 0 ;;
    *) return 1 ;;
  esac
}

winpath_to_posix() {
  local winpath="$1"

  if has_cmd cygpath; then
    cygpath -u "$winpath"
    return 0
  fi

  # Minimal conversion for paths like C:\xampp\php\php.exe.
  local drive rest
  drive="${winpath:0:1}"
  rest="${winpath:2}"
  drive="$(printf '%s' "$drive" | tr 'A-Z' 'a-z')"
  rest="${rest//\\//}"
  printf '/%s%s\n' "$drive" "$rest"
}

resolve_via_where() {
  local exe_name="$1"
  if ! is_windows_bash; then
    return 1
  fi
  if ! has_cmd where.exe; then
    return 1
  fi

  local winpath
  winpath="$(where.exe "$exe_name" 2>/dev/null | head -n 1 | tr -d '\r')"
  if [ -z "$winpath" ]; then
    return 1
  fi

  winpath_to_posix "$winpath"
}

resolve_php() {
  if has_cmd php; then
    echo "php"
    return 0
  fi

  # Common Windows XAMPP installs.
  for candidate in \
    "/c/xampp/php/php.exe" \
    "/c/XAMPP/php/php.exe" \
    "/c/Program Files/xampp/php/php.exe" \
    "/c/Program Files/XAMPP/php/php.exe" \
    "/mnt/c/xampp/php/php.exe" \
    "/mnt/c/XAMPP/php/php.exe" \
    "/mnt/c/Program Files/xampp/php/php.exe" \
    "/mnt/c/Program Files/XAMPP/php/php.exe"; do
    if [ -f "$candidate" ]; then
      echo "$candidate"
      return 0
    fi
  done

  local detected
  detected="$(resolve_via_where php.exe || true)"
  if [ -n "$detected" ] && [ -f "$detected" ]; then
    echo "$detected"
    return 0
  fi

  return 1
}

resolve_rg() {
  if has_cmd rg; then
    echo "rg"
    return 0
  fi

  local detected
  detected="$(resolve_via_where rg.exe || true)"
  if [ -n "$detected" ] && [ -f "$detected" ]; then
    echo "$detected"
    return 0
  fi

  return 1
}

scan_for_secrets() {
  # Prefer ripgrep for speed/consistency, but fall back to grep on systems
  # where rg isn't installed (common on Windows dev machines).
  local rg_bin
  rg_bin="$(resolve_rg || true)"

  if [ -n "$rg_bin" ]; then
    if "$rg_bin" -n \
      -e "AIza" \
      -e "AKIA" \
      -e "-----BEGIN" \
      -e "client_secret\\s*[:=]\\s*[\"'][^\"']{10,}[\"']" \
      -e "refresh_token\\s*[:=]\\s*[\"'][^\"']{10,}[\"']" \
      --glob "!scripts/verify.sh"; then
      return 0
    fi

    return 1
  fi

  if ! has_cmd grep; then
    printf "rg (ripgrep) not found and grep not available; cannot run secret scan.\n" >&2
    return 2
  fi

  # grep -E doesn't support \\s, use POSIX character classes instead.
  # Scan the whole repo (no exclusions) but exclude this script to avoid
  # false positives from patterns.
  if grep -RInE \
    --exclude="verify.sh" \
    "AIza|AKIA|-----BEGIN|client_secret[[:space:]]*[:=][[:space:]]*['\"][^'\"]{10,}['\"]|refresh_token[[:space:]]*[:=][[:space:]]*['\"][^'\"]{10,}['\"]" \
    .; then
    return 0
  fi

  return 1
}

printf "Running php -l on plugin PHP files...\n"
php_bin="$(resolve_php || true)"
if [ -z "$php_bin" ]; then
	printf "php not found. Install PHP or add it to PATH. On Windows with XAMPP, php.exe is usually at C:\\xampp\\php\\php.exe.\n" >&2
	exit 1
fi

if ! "$php_bin" -v >/dev/null 2>&1; then
  printf "Resolved php at '%s' but it is not runnable from this shell. Check file permissions / execution policy.\n" "$php_bin" >&2
  exit 1
fi

find "${php_paths[@]}" -type f -name "*.php" -print0 \
	| xargs -0 -r -n1 "$php_bin" -l

printf "Scanning for obvious secret markers...\n"
if scan_for_secrets; then
  printf "\nSecret marker(s) detected. Remove secrets before proceeding.\n" >&2
  exit 1
fi

printf "\nVerify checks passed.\n"
