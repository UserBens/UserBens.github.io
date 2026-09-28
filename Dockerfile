# Dockerfile untuk deploy Laravel Portfolio ke Render (free web service)
FROM php:8.3-cli

# Install ekstensi & dependency dasar (sqlite cukup untuk portofolio, gratis, tanpa DB server terpisah)
RUN apt-get update && apt-get install -y \
    git unzip libsqlite3-dev libzip-dev libpng-dev \
    && docker-php-ext-install pdo pdo_sqlite zip gd \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

RUN composer install --no-dev --optimize-autoloader --no-interaction \
    && cp .env.example .env \
    && touch database/database.sqlite \
    && php artisan key:generate \
    && php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

# Render menyuntikkan variabel $PORT secara otomatis (free tier)
EXPOSE 10000
CMD php artisan migrate --force && \
    php artisan serve --host 0.0.0.0 --port ${PORT:-10000}
