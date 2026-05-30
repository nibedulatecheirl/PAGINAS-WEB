#!/usr/bin/env bash
set -euo pipefail

APP_DIR="${APP_DIR:-/srv/apps/crm-colegio/current}"
BACKUP_DIR="${BACKUP_DIR:-/srv/apps/crm-colegio/backups}"
STAMP="$(date +%Y%m%d-%H%M%S)"

cd "$APP_DIR"
mkdir -p "$BACKUP_DIR"

set -a
source .env
set +a

docker compose exec -T db mariadb-dump \
  -u"$DB_USERNAME" \
  -p"$DB_PASSWORD" \
  --single-transaction \
  --routines \
  --triggers \
  "$DB_DATABASE" | gzip > "$BACKUP_DIR/colegio_crm-$STAMP.sql.gz"

tar -czf "$BACKUP_DIR/crm_colegio_files-$STAMP.tar.gz" \
  --exclude="public/index.php" \
  .env \
  storage/app \
  public

find "$BACKUP_DIR" -type f -mtime +14 -delete

echo "Backup creado en $BACKUP_DIR con fecha $STAMP"
