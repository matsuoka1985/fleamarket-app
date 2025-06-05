# ─────────────────────────────────────────────
# Stage 0 : 依存ビルド（Composer / npm build）
# ─────────────────────────────────────────────
FROM php:8.1-fpm AS build

COPY php.ini /usr/local/etc/php/

# PHP 拡張・Node.js
RUN apt-get update -y && apt-get install -y \
  curl gnupg2 ca-certificates unzip zlib1g-dev libzip-dev \
  default-mysql-client \
  && curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
  && apt-get install -y nodejs \
  && docker-php-ext-install pdo_mysql zip

# Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www

# Composer 依存
COPY src/composer.json src/composer.lock ./
RUN composer install --no-dev --prefer-dist --no-progress --ansi

# npm 依存＋ビルド
COPY src/package*.json ./
RUN npm ci --silent
COPY src/resources ./resources
RUN npm run build    # Vite/Mix のコマンドに合わせて変更可

# ─────────────────────────────────────────────
# Stage 1 : 実行イメージ
# ─────────────────────────────────────────────
FROM php:8.1-fpm AS runtime

COPY php.ini /usr/local/etc/php/
RUN apt-get update -y && apt-get install -y \
  default-mysql-client zlib1g-dev libzip-dev \
  && docker-php-ext-install pdo_mysql zip \
  && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www

# ビルド成果物
COPY --from=build /var/www/vendor       ./vendor
COPY --from=build /var/www/node_modules ./node_modules
COPY --from=build /var/www/public       ./public
COPY src .

# Entrypoint
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh
ENTRYPOINT ["entrypoint.sh"]

CMD ["php-fpm"]
