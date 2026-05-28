#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$ROOT_DIR"

log() {
  printf "\n[%s] %s\n" "$(date +'%H:%M:%S')" "$1"
}

warn() {
  printf "\n[WARN] %s\n" "$1"
}

die() {
  printf "\n[ERROR] %s\n" "$1" >&2
  exit 1
}

require_cmd() {
  command -v "$1" >/dev/null 2>&1 || die "Required command '$1' not found in PATH."
}

php_version_ok() {
  php -r 'exit(version_compare(PHP_VERSION, "8.3.0", ">=") ? 0 : 1);'
}

node_version_ok() {
  node -e '
    const [maj, min] = process.versions.node.split(".").map(Number);
    const ok = (maj > 20) || (maj === 20 && min >= 19);
    process.exit(ok ? 0 : 1);
  '
}

require_cmd php
require_cmd node
require_cmd npm

log "Starting local verification in $ROOT_DIR"

if ! php_version_ok; then
  CURRENT_PHP="$(php -r 'echo PHP_VERSION;')"
  die "PHP >= 8.3.0 required. Current PHP is $CURRENT_PHP."
fi

if ! node_version_ok; then
  CURRENT_NODE="$(node -p 'process.versions.node')"
  warn "Node 20.19+ recommended by Vite. Current Node is $CURRENT_NODE."
fi

if [ ! -d vendor ]; then
  die "vendor directory missing. Run: composer install"
fi

if [ ! -d node_modules ]; then
  die "node_modules missing. Run: npm install"
fi

if [ ! -x ./vendor/bin/pest ]; then
  die "Pest binary missing at ./vendor/bin/pest. Run: composer install"
fi

log "Running migrations"
php artisan migrate --no-interaction

log "Generating Ziggy routes"
php artisan ziggy:generate

log "Running focused feature tests"
./vendor/bin/pest tests/Feature/PropertySlugTest.php

log "Building frontend (client + SSR)"
npm run build

log "Verification complete: all checks passed."
