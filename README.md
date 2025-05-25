# 1. リポジトリをクローン
git clone git@github.com:matsuoka1985/fleamarket-app.git
cd fleamarket-app

# 2. .envファイルを作成
cp .env.example .env

# 3. コンテナを起動
docker compose up -d --build

# 4. composer install（PHPコンテナの中で実行）
docker compose exec app composer install

# 5. アプリキー生成
docker compose exec app php artisan key:generate

# 6. マイグレーション
docker compose exec app php artisan migrate