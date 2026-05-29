#!/usr/bin/env bash
set -euo pipefail

APP_DIR="${APP_DIR:-/srv/apps/beniglow-store/current}"
cd "$APP_DIR"

git pull --ff-only

docker compose run -T --rm app composer install --no-dev --optimize-autoloader </dev/null

if [ -f package-lock.json ]; then
  docker compose run -T --rm node npm ci </dev/null
  docker compose run -T --rm node npm run build:store </dev/null
fi

docker compose exec -T app php artisan migrate --force </dev/null
docker compose exec -T app php artisan storage:link </dev/null
docker compose exec -T app php artisan config:cache </dev/null
docker compose exec -T app php artisan route:cache </dev/null
docker compose exec -T app php artisan view:cache </dev/null

docker compose up -d --force-recreate app web
