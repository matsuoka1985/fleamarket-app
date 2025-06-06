

# セットアップ手順


-----

##  セットアップ

### 1\. リポジトリのクローン

まず、プロジェクトのリポジトリをローカルにクローンし、ディレクトリに移動します。

```bash
git clone git@github.com:matsuoka1985/fleamarket-app.git
cd fleamarket-app
```

### 2\. Dockerコンテナの起動



```bash
docker compose up -d --build
```

**🚀 起動完了の確認:**
PHPコンテナがリクエストを受け付けられる状態になると、以下のコマンドの出力に `NOTICE: ready to handle connections` と表示されます。この表示が出たら、アプリケーションの起動が完了し、ブラウザからアクセスできる状態です。

```bash
docker compose logs -f php
```

-----

##  テスト


### 1\. PHPUnitテストの実行


```bash
php artisan test
```

### 2\. Laravel Duskテストの実行


```bash
php artisan dusk
```

-----

##  アクセス情報 & コンテナアクセス

全てのコンテナが起動し、アプリケーションのセットアップが完了すると、以下のURLで各サービスにアクセスできます。

  * **Laravel アプリケーション**: [http://localhost:80](https://www.google.com/search?q=http://localhost:80)
  * **MailHog (開発用メール UI)**: [http://localhost:8025](https://www.google.com/search?q=http://localhost:8025)

### PHPコンテナへのアクセス

PHPコンテナのシェルに入るには、以下のコマンドを使用します。

```bash
docker compose exec php bash
```


#### Stripe APIキーの追記

`.env` ファイルにStripeのAPIキーを追記してください。キーは別途共有されます。




-----