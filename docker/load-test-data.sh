#!/bin/bash
# Load test data into the Docker Compose MySQL database.
#
# Usage:
#   ./scripts/load-test-data.sh
#   DB_DATABASE=animaisderua DB_USERNAME=animaisderua DB_PASSWORD=secret ./scripts/load-test-data.sh

set -e

DB_DATABASE="${DB_DATABASE:-animaisderua}"
DB_USERNAME="${DB_USERNAME:-animaisderua}"
DB_PASSWORD="${DB_PASSWORD:-secret}"

SCRIPT_DIR="$(cd "$(dirname "$0")" && pwd)"
SQL_FILE="$SCRIPT_DIR/test-data.sql"

echo "Loading test data into the MySQL Docker container..."

docker compose cp "$SQL_FILE" db:/tmp/test-data.sql
docker compose exec db mysql --default-character-set=utf8mb4 -u"$DB_USERNAME" -p"$DB_PASSWORD" "$DB_DATABASE" -e "source /tmp/test-data.sql"

echo "Done."
