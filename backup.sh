#!/bin/bash
# Nightly DB backup for the prod VM (also works in dev).
# Dumps propertyspot DB inside the propertyspot_db container, gzips it,
# keeps KEEP_DAYS days of backups, optionally copies to S3 via aws cli.
#
# Cron (VM): 15 4 * * * /opt/propertyspot/backup.sh >> /var/log/propertyspot-backup.log 2>&1
set -euo pipefail

BACKUP_DIR="${BACKUP_DIR:-/var/backups/propertyspot}"
KEEP_DAYS="${KEEP_DAYS:-14}"
DB_CONTAINER="${DB_CONTAINER:-propertyspot_db}"
DB_USER="${DB_USER:-propertyspot}"

mkdir -p "$BACKUP_DIR"
PW=$(grep '^MYSQL_PASSWORD=' "$(dirname "$0")/.env" | cut -d= -f2-)

STAMP=$(date +%Y%m%d_%H%M%S)
FILE="$BACKUP_DIR/propertyspot_$STAMP.sql.gz"

docker exec "$DB_CONTAINER" /usr/bin/mysqldump \
  -u"$DB_USER" -p"$PW" \
  --single-transaction --routines --triggers --no-tablespaces \
  propertyspot | gzip > "$FILE"

echo "$(date '+%Y-%m-%d %H:%M:%S') wrote $FILE ($(du -h "$FILE" | cut -f1))"

find "$BACKUP_DIR" -name 'propertyspot_*.sql.gz' -mtime +"$KEEP_DAYS" -delete

# Optional offsite copy — set S3_BUCKET in the environment to enable:
#   BACKUP_DIR=/var/backups/propertyspot S3_BUCKET=my-bucket ./backup.sh
if [ -n "${S3_BUCKET:-}" ] && command -v aws >/dev/null 2>&1; then
  aws s3 cp "$FILE" "s3://$S3_BUCKET/mysql/" --sse AES256
  echo "uploaded to s3://$S3_BUCKET/mysql/"
fi