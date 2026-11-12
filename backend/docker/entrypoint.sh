#!/bin/sh
set -e
cd /var/www/html

# A mounted storage volume may be missing Laravel's expected sub-folders.
mkdir -p storage/app/public \
         storage/framework/cache/data \
         storage/framework/sessions \
         storage/framework/views \
         storage/logs \
         bootstrap/cache

if [ -z "${APP_KEY:-}" ]; then
    echo "[entrypoint] WARNING: APP_KEY is empty - Laravel will fail to encrypt/decrypt." >&2
fi

if [ "${APP_ENV:-production}" != "local" ]; then
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    php artisan event:cache
fi

# Enabled on ONE container only (the `backend` service) to avoid migration races.
if [ "${RUN_MIGRATIONS:-false}" = "true" ]; then
    echo "[entrypoint] running migrations"
    php artisan migrate --force
fi

exec "$@"
