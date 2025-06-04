

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
















プロフィール画像を変更されたら、dbだけでなく、ファイルそのものを前のものについては削除する必要がある。


メール認証送信ページには既にメール認証が済んだユーザーは到達できない方が良いか。


あるページにアクセスしようとしたけど、ログインしてなかったりしてログインページにリダイレクトされてしまった時に、ログイン後にまた最初にアクセスを試みたページにアクセスさせてあげるのがユーザーフレンドリー？そういう機能って実装可能？


現状決済手段がurlのクエリパラメータについている状態なので、これをなんとかしたい。


完成後に別にブランチ切ってコメントで自分でわからないコードを自分できちんと読む。


ファットコントローラーがあったらサービスクラスにまとめる。

ユーザープロフィール画面のプロフィール画像表示箇所が丸いのがおかしいので直す。


郵便番号のvalidationの結果がおかしい。数字じゃない文字列を入れると郵便番号を入力してください。と返ってくるけど本来欲しいのは、郵便番号は7桁の数字で入力してください。エラーメッセージ。郵便番号を入力してください。というエラーメッセージは何も空で送信してきた時に表示するエラーメッセージ。


・.env.testingファイル作成手順をREADME.mdファイルに追記。

・サービスクラス作成・ファットコントローラー直す・・bladeに長々と記述されたロジックをbladeから追い出す。

・




・コードへのコメント・あるいは自分用の解説コメント付与


・スプレッドシートの内容が全てできているか確認


・モバイル用のヘッダーメニューについてはそれを表示した状態でまたブラウザ幅を広くするときちんと自動で消えるようにする。現状ではモバイル用のヘッダーを表示した状態でブラウザ幅を広げるとそのままモバイル用のヘッダーが表示されてかつpc用のヘッダーも表示されるようになる。


・jsファイルはbladeファイルに下手がきせずに別ファイルに切り出す。


・return redirect()->intended('/?tab=mylist’);について調べる。可能ならばアプリにおいて積極的に利用していく。



・コメントの入力欄のtextareaについてはおそらくpaddingがないせいか、textareaに最初カーソルを合わせてもそのカーソルが見えないので分かりづらい。修正が必要。


・//決済成功後の処理。ここで注文情報を保存する。最後にユーザーにthanksページを表示する。
Route::get('/checkout/success/{item}', [OrderController::class, 'success'])->name('orders.success');
これは本当にgetメソッドでいい処理？postメソッドを使用すべきでは？

ログアウト時は、ログアウトボタンを押したページが別に認証必要なしにアクセスできるページであればそのまま元のページにリダイレクトされるようにしたい。

フロントでのvalidationでの文字のアレンジの仕方について調べる。


ER図をスプレッドシートに貼るのを忘れないようにする。


郵便番号を入力する際に桁数が多い場合は数字で入力してくださいというエラーメッセージが出る。


Stripeのmock関係のでlocalhostなんとかってurlが表示されていたけどベタがきまずいんじゃない？


商品購入画面の
http://localhost/purchase/3?
という最後の?が気になる？なんだこれ？


・今度はプロフィールページが背景グレイで四角くなった。背景いらない。


novalidateを開発の確認時にだけうまくつける方法はあるか。本番においてはnovalidateをつけたい。


ProfileViewTestにおいては既存の画像を使っているけどこれをきちんとダミー画像生成してファイルパスをdbに保存するだけでなく、画像ファイルそのものもアップロードできているかどうかも確認する必要があるかも。


商品詳細ページにおいてプロフィール画像がきちんと表示されていないので直す。ダミーの画像が入っているだけ。


商品購入時に何度いつのタイミングでまだ商品が売れていないかどうか確認を挟むべきか。現状最後くらい？


README.mdにおいてdockerfileやdocker-compose.yamlファイルにまとめることができるものについてはそこに入れる。
