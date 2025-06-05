#!/bin/sh
set -e

# 1) .env が無ければコピーして APP_KEY 生成
if [ ! -f /var/www/.env ]; then
  cp /var/www/.env.example /var/www/.env
  php /var/www/artisan key:generate --ansi
fi

# 2) migrate & storage:link (初回だけ。失敗しても継続)
php /var/www/artisan migrate --force   || true
php /var/www/artisan storage:link      || true

# 3) パーミッション (ログ/キャッシュ)
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

exec "$@"
