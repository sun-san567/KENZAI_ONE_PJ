# PHP コンテナのベースイメージ
FROM php:8.4-fpm

# Node.js をインストール（公式リポジトリを使用）
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g npm

# その他の必要なパッケージ
RUN apt-get update && apt-get install -y \
    git unzip curl libpng-dev libjpeg-dev libfreetype6-dev \
    && docker-php-ext-install pdo pdo_mysql gd

WORKDIR /var/www

# Composer のインストール
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Laravel プロジェクトをコピー
COPY . .

RUN composer install --no-dev --optimize-autoloader

# 設定の調整
RUN php artisan storage:link
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

CMD ["php-fpm"]
