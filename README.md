# 1. リポジトリをクローン
```git clone git@github.com:matsuoka1985/fleamarket-app.git```

cd fleamarket-app

# 2. コンテナを起動
docker compose up -d --build

# 3. phpコンテナに入る
docker-compose exec php bash

# 5. .envファイルを作成（以下は全てPHPコンテナの中で実行）
cp .env.example .env

# 6. composer install
composer install

# 7. アプリキー生成
php artisan key:generate

# 8. マイグレーション
php artisan migrate

# 9. ストレージリンク作成（画像などのアップロード表示に必要）
php artisan storage:link

# 10. npmの依存パッケージインストール
npm install

# 11. 開発用ビルド
npm run dev

# 12. Dusk テスト実行手順 (`.env.dusk.local.example` をコピーして `.env.dusk.local` を作成)
cp .env.dusk.local.example .env.dusk.local