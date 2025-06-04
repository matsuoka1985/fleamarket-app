# 1. リポジトリをクローン
```git clone git@github.com:matsuoka1985/fleamarket-app.git```

```cd fleamarket-app```

# 2. コンテナを起動
```docker compose up -d --build```

# 3. phpコンテナに入る
```docker-compose exec php bash```

# 5. .envファイルを作成（以下は全てPHPコンテナの中で実行）
```cp .env.example .env```

# 6. composer install
```composer install```

# 7. アプリキー生成
```php artisan key:generate```

# 8. マイグレーションとシーディング
```php artisan migrate --seed```

# 9. ストレージリンク作成（画像などのアップロード表示に必要）
```php artisan storage:link```

# 10. npmの依存パッケージインストール
```npm install```

# 11 MailHogの使い方。
開発用のメール送受信にMailhogを使用しています。
MailHogのWeb UIには以下のURLでアクセスできます。
```http://localhost:8025```


# 12.  PHPUnitテスト実行手順 (.env.testing.exampleをコピーして.env.testingを作成)
```cp .env.testing.example .env.testing```
マイグレートとシーディング実行
```php artisan migrate --env=test --seed```
テスト実行
```php artisan test```

別途連絡するStripeのAPIキーを作成した.env.testingファイルに追記してください。

# 13. Dusk テスト実行手順 (.env.dusk.local.example をコピーして.env.dusk.localを作成)
```cp .env.dusk.local.example .env.dusk.local```
マイグレートとシーディング実行
```php artisan migrate --env=dusk --seed```
ブラウザテスト実行
```php artisan dusk```
