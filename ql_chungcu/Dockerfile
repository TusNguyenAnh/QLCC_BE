FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev \
    zip unzip nginx supervisor libzip-dev

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy all application files
COPY . .

# Verify files were copied
RUN echo "=== Checking copied files ===" \
    && ls -la \
    && echo "=== Checking artisan ===" \
    && test -f artisan && echo "✓ artisan found" || (echo "✗ artisan NOT found" && exit 1) \
    && test -f composer.json && echo "✓ composer.json found" || (echo "✗ composer.json NOT found" && exit 1)

# Copy environment file
COPY .env.docker .env

# Install composer dependencies
RUN echo "=== Installing Composer dependencies ===" \
    && composer --version \
    && composer install --no-dev --optimize-autoloader --no-interaction --verbose \
    && echo "✓ Composer install completed"

# Verify vendor directory
RUN test -d vendor && echo "✓ vendor directory exists" || (echo "✗ vendor NOT found" && exit 1)

# Set permissions
RUN chmod +x artisan

COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/default.conf /etc/nginx/conf.d/default.conf

COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

RUN mkdir -p /var/www/html/storage/app/public \
    && mkdir -p /var/www/html/storage/framework/{cache,sessions,views} \
    && mkdir -p /var/www/html/storage/logs \
    && mkdir -p /var/www/html/bootstrap/cache \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

EXPOSE 80

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]