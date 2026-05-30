#!/usr/bin/env bash
set -euo pipefail

cd /srv/apps/crm-colegio/current

if [ -x scripts/backup-crm-colegio.sh ]; then
  scripts/backup-crm-colegio.sh
fi

git pull --ff-only
docker compose run -T --rm app composer install --no-dev --optimize-autoloader
docker compose exec -T app php artisan migrate --force
docker compose exec -T app php artisan config:cache
docker compose exec -T app php artisan route:cache
docker compose exec -T app php artisan view:cache
docker compose up -d --force-recreate app web
sudo systemctl reload nginx
