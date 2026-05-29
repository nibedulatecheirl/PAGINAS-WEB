#!/usr/bin/env bash
set -euo pipefail

APP_DIR="${APP_DIR:-/srv/apps/beniglow-store/current}"
BACKUP_DIR="${BACKUP_DIR:-/srv/apps/beniglow-store/backups}"
KEEP_DAYS="${KEEP_DAYS:-14}"
STAMP="$(date +%Y%m%d-%H%M%S)"

mkdir -p "$BACKUP_DIR"
cd "$APP_DIR"

docker compose exec -T db sh -c 'mariadb-dump -uroot -p"$MARIADB_ROOT_PASSWORD" "$MARIADB_DATABASE"' </dev/null \
  | gzip > "$BACKUP_DIR/beniglow_store-$STAMP.sql.gz"

tar -czf "$BACKUP_DIR/beniglow_files-$STAMP.tar.gz" \
  .env \
  storage \
  public/uploads \
  public/store/assets \
  public/store/dist

find "$BACKUP_DIR" -type f -mtime +"$KEEP_DAYS" -delete

echo "Backup creado en $BACKUP_DIR con fecha $STAMP"
