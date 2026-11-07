#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$ROOT_DIR"

if ! command -v docker >/dev/null 2>&1; then
    echo "Docker is required. Install Docker Desktop, then retry."
    exit 1
fi

if [ ! -f .env ]; then
    cp .env.example .env
    echo "Created .env from .env.example"
fi

export WWWUSER="${WWWUSER:-$(id -u 2>/dev/null || echo 1000)}"
export WWWGROUP="${WWWGROUP:-$(id -g 2>/dev/null || echo 1000)}"

echo "==> Building and starting containers..."
docker compose up -d --build

DB_PASSWORD="$(grep -E '^DB_PASSWORD=' .env | cut -d= -f2- || true)"
DB_PASSWORD="${DB_PASSWORD:-password}"

echo "==> Waiting for MySQL..."
attempts=0
until docker compose exec -T mysql mysqladmin ping -h 127.0.0.1 -p"${DB_PASSWORD}" --silent >/dev/null 2>&1; do
    attempts=$((attempts + 1))
    if [ "$attempts" -ge 40 ]; then
        echo "MySQL did not become ready in time."
        exit 1
    fi
    sleep 2
done

echo "==> Installing PHP dependencies..."
docker compose exec -T laravel.test composer install --no-interaction

if ! grep -q '^APP_KEY=base64:' .env; then
    echo "==> Generating application key..."
    docker compose exec -T laravel.test php artisan key:generate --force
fi

echo "==> Migrating and seeding database..."
docker compose exec -T laravel.test php artisan migrate --force
docker compose exec -T laravel.test php artisan db:seed --force
docker compose exec -T laravel.test php artisan storage:link || true

echo "==> Installing and building frontend assets..."
docker compose exec -T laravel.test npm install
docker compose exec -T laravel.test npm run build

APP_PORT="$(grep -E '^APP_PORT=' .env | cut -d= -f2- || true)"
APP_PORT="${APP_PORT:-80}"
APP_URL="$(grep -E '^APP_URL=' .env | cut -d= -f2- | tr -d '"' || true)"
APP_URL="${APP_URL:-http://localhost}"

echo ""
echo "Ready."
echo "  App:   ${APP_URL}  (host port ${APP_PORT})"
echo "  Admin: admin@example.com / password"
echo ""
echo "Useful commands:"
echo "  docker compose exec laravel.test php artisan test --compact"
echo "  docker compose exec laravel.test php artisan migrate:fresh --seed --force"
echo "  docker compose down"
