# ──────────────────────────────
# Stage 0 : build
# ──────────────────────────────
FROM php:8.1-fpm AS build

WORKDIR /var/www

# 依存パッケージ
RUN apt-get update -y && \
  apt-get install -y --no-install-recommends \
  git unzip zlib1g-dev libzip-dev \
  curl ca-certificates gnupg lsb-release \
  default-mysql-client \
  && curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
  && apt-get install -y nodejs

RUN docker-php-ext-install pdo_mysql zip

# Composer
RUN curl -sS https://getcomposer.org/installer | php -- \
  --install-dir=/usr/local/bin --filename=composer

# ──────────────────────────────
# ① **まず全ソースをコピー**
#    → App\Console\Kernel などが存在する状態で
#      composer install を実行できる
# ──────────────────────────────
COPY src .

# PHP 依存（dev も入れる）
RUN composer install --prefer-dist --optimize-autoloader --no-interaction

# Node 依存 & ビルド
RUN npm ci && npm run production

# ──────────────────────────────
# Stage 1 : runtime
# ──────────────────────────────
FROM php:8.1-fpm AS runtime

WORKDIR /var/www

RUN apt-get update -y && \
  apt-get install -y --no-install-recommends \
  zlib1g-dev libzip-dev default-mysql-client && \
  docker-php-ext-install pdo_mysql zip && \
  rm -rf /var/lib/apt/lists/*

# php.ini
COPY docker/php/php.ini /usr/local/etc/php/php.ini

# build 産物
COPY --from=build /var/www /var/www

# Entrypoint
COPY docker/php/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh
ENTRYPOINT ["entrypoint.sh"]

CMD ["php-fpm"]
