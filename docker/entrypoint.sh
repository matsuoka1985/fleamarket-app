#!/usr/bin/env bash
set -e

cd /var/www

# .env が無ければ example から作成
if [ ! -f .env ]; then
  cp .env.example .env
fi

# APP_KEY 無ければ生成
if ! grep -q '^APP_KEY=' .env || [ -z "$(grep '^APP_KEY=' .env | cut -d '=' -f2)" ]; then
  php artisan key:generate --force --ansi
fi

# DB マイグレーション & シーディング（冪等）
php artisan migrate --seed --force

# storage:link（既存ならスキップ）
php artisan storage:link || true

exec "$@"
