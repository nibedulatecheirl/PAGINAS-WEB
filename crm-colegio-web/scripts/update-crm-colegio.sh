#!/usr/bin/env bash
set -euo pipefail

cd /srv/apps/crm-colegio/current

if [ -x scripts/backup-crm-colegio.sh ]; then
  scripts/backup-crm-colegio.sh
fi

if [ -d .git ]; then
  git pull --ff-only
else
  echo "No hay repositorio Git en este despliegue; se omite git pull."
fi

mkdir -p \
  storage/app/public \
  storage/framework/cache/data \
  storage/framework/sessions \
  storage/framework/views \
  storage/framework/testing \
  storage/logs \
  bootstrap/cache

docker compose run -T --rm app composer install --no-dev --optimize-autoloader
docker compose exec -T app php artisan migrate --force
docker compose exec -T app php artisan storage:link --force
docker compose exec -T app php artisan config:cache
docker compose exec -T app php artisan route:cache
docker compose exec -T app php artisan view:cache
chown -R 33:33 storage bootstrap/cache
docker compose up -d --force-recreate app web
systemctl reload nginx
