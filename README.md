

````markdown
# セットアップ手順

---

## 1. リポジトリのクローン

```bash
git clone git@github.com:matsuoka1985/fleamarket-app.git
cd fleamarket-app
````

---

## 2. コンテナの起動

```bash
docker compose up -d --build
```

---

## 3. PHPコンテナに入る

```bash
docker-compose exec php bash
```

---

## 4. `.env` ファイルの作成と Stripe キー追記（PHPコンテナ内）

```bash
cp .env.example .env
```

※ `.env` に Stripe の API キーを追記（キーは別途連絡）

---

## 5. Laravel セットアップ（PHPコンテナ内）

```bash
composer install
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
```

---

## 6. フロントエンドのセットアップ（PHPコンテナ内）

```bash
npm install
```

---

## 7. PHPUnit テスト実行手順

### 7.1 `.env.testing` の作成（PHPコンテナ内）

```bash
cp .env.testing.example .env.testing
```

### 7.2 テスト用 DB の作成

```bash
docker-compose exec mysql bash
```

```bash
mysql -u root -p
# パスワード：root

CREATE DATABASE demo_test;
exit
```

### 7.3 マイグレーション & シーディング（PHPコンテナ内）

```bash
docker-compose exec php bash
```

```bash
php artisan migrate --env=testing --seed
php artisan key:generate --env=testing

# 失敗時の対処
php artisan config:clear
```

### 7.4 テストの実行

```bash
php artisan test
```

---

## 8. Laravel Dusk（ブラウザテスト）実行手順

### 8.1 `.env.dusk.local` の作成（PHPコンテナ内）

```bash
cp .env.dusk.local.example .env.dusk.local
```

### 8.2 APP\_KEY の生成

```bash
php artisan key:generate --env=dusk.local
```

### 8.3 Dusk テストの実行

```bash
php artisan dusk
```

---

## 9. アクセス情報

* Laravel アプリケーション: [http://localhost:80](http://localhost:80)
* MailHog（開発用メール UI）: [http://localhost:8025](http://localhost:8025)

---


