
# ---------- app: PHP-FPM ----------
FROM php:8.4-fpm-alpine AS app

RUN apk add --no-cache \
        icu-dev \
        libzip-dev \
        oniguruma-dev \
        git \
        unzip \
    && docker-php-ext-configure intl \
    && docker-php-ext-install -j"$(nproc)" \
        pdo_mysql \
        mbstring \
        bcmath \
        intl \
        zip \
        pcntl \
        exif \
        opcache

RUN { \
        echo 'opcache.memory_consumption=192'; \
        echo 'opcache.interned_strings_buffer=16'; \
        echo 'opcache.max_accelerated_files=20000'; \
        echo 'opcache.validate_timestamps=0'; \
    } > /usr/local/etc/php/conf.d/opcache-prod.ini

COPY docker/www-clearenv.conf /usr/local/etc/php-fpm.d/zz-clearenv.conf

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json composer.lock ./
RUN composer install \
        --no-dev \
        --no-scripts \
        --no-autoloader \
        --no-interaction \
        --prefer-dist

COPY . .

RUN composer dump-autoload --optimize --no-dev --no-interaction \
    && mkdir -p \
        storage/app/public \
        storage/framework/cache/data \
        storage/framework/sessions \
        storage/framework/views \
        storage/logs \
        bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

USER www-data
EXPOSE 9000
ENTRYPOINT ["entrypoint.sh"]
CMD ["php-fpm"]


# ---------- web: nginx ----------
FROM nginx:1.27-alpine AS web

COPY --from=app /var/www/html/public /var/www/html/public
COPY docker/nginx.conf /etc/nginx/conf.d/default.conf
