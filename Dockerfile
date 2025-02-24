# ベースイメージとしてPHP 8.4 を使用
FROM php:8.4-fpm

# 必要なパッケージをインストール
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-install pdo pdo_mysql gd

# Composer をインストール
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 作業ディレクトリを設定
WORKDIR /var/www

# Laravel プロジェクトのファイルをコピー
COPY . .

# Composer の依存関係をインストール
RUN composer install --no-dev --optimize-autoloader

# Laravel のストレージリンクを作成
RUN php artisan storage:link

# Laravel のパーミッション設定
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Laravel のサーバーを起動
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]

EXPOSE 8000

