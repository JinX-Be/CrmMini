#!/usr/bin/env bash
set -e

# Render injects $PORT (defaults to 10000). Apache must listen on it.
: "${PORT:=10000}"
sed -ri "s/^Listen .*/Listen ${PORT}/" /etc/apache2/ports.conf
sed -ri "s/:80>/:${PORT}>/" /etc/apache2/sites-available/000-default.conf

# Writable runtime dirs (filesystem is ephemeral on Render free tier).
chown -R www-data:www-data storage bootstrap/cache || true

# Discover packages + cache config & views (route:cache skipped — health route).
php artisan package:discover --ansi
php artisan config:cache
php artisan view:cache

# Apply any pending migrations. No shell/one-off jobs on Render free tier,
# so this runs on every container start (idempotent — only pending run).
php artisan migrate --force

exec apache2-foreground
