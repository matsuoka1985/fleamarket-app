#!/bin/sh
# entrypoint for php
set -eu

# ───────────────────────── composer ─
composer install --no-interaction --prefer-dist

# ───────────────────────── npm install ─
# node_modules が無いときだけ実行して高速化
if [ ! -d /var/www/node_modules ]; then
  npm install --loglevel=error        
fi

# ───────────────────────── .env & APP_KEY ─
if [ ! -f /var/www/.env ]; then
  cp /var/www/.env.example /var/www/.env
  php /var/www/artisan key:generate --ansi
  echo "# Stripe KEY は手動追記" >> /var/www/.env
fi

# ───────────────────────── .env.testing ─
if [ ! -f /var/www/.env.testing ]; then
  cp /var/www/.env.testing.example  /var/www/.env.testing  2>/dev/null \
    || cp /var/www/.env /var/www/.env.testing
  php /var/www/artisan key:generate --env=testing --ansi
fi

# ───────────────────────── .env.dusk.local ─
if [ ! -f /var/www/.env.dusk.local ]; then
  cp /var/www/.env.dusk.local.example /var/www/.env.dusk.local 2>/dev/null \
    || cp /var/www/.env /var/www/.env.dusk.local
  php /var/www/artisan key:generate --env=dusk.local --ansi
fi

# ───────────────────────── wait mysql ─
DB_HOST="${DB_HOST:-mysql}"
DB_ROOT_PASSWORD="${DB_ROOT_PASSWORD:-root}"
until mysqladmin ping -h"$DB_HOST" -uroot -p"$DB_ROOT_PASSWORD" --silent; do
  sleep 2
done

# ───────────────────────── migrate & seed (本番) ─
php /var/www/artisan migrate --force

SEED_FLAG=/var/www/.seeded
if [ ! -f "$SEED_FLAG" ]; then
  php /var/www/artisan db:seed --force
  touch "$SEED_FLAG"
fi

# ───────────────────────── migrate & seed (testing) ─
TEST_FLAG=/var/www/.seeded_testing
if [ ! -f "$TEST_FLAG" ]; then
  # test 用 DB が無い場合はここで自動作成
  mysql -h"$DB_HOST" -uroot -p"$DB_ROOT_PASSWORD" \
        -e "CREATE DATABASE IF NOT EXISTS \`demo_test\` \
            CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
  php /var/www/artisan migrate --env=testing --force
  php /var/www/artisan db:seed --env=testing --force
  php /var/www/artisan config:clear
  touch "$TEST_FLAG"
fi

# ───────────────────────── storage link & perms ─
php /var/www/artisan storage:link || true
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

exec "$@"
