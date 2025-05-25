# 1. リポジトリをクローン
git clone git@github.com:matsuoka1985/fleamarket-app.git

cd fleamarket-app

# 2. コンテナを起動
docker compose up -d --build

# 3. phpコンテナに入る
docker-compose exec php bash

# 5. .envファイルを作成（PHPコンテナの中で実行）
cp .env.example .env

# 6. composer install（PHPコンテナの中で実行）
composer install

# 7. アプリキー生成（PHPコンテナの中で実行）
php artisan key:generate

# 8. マイグレーション（PHPコンテナの中で実行）
php artisan migrate