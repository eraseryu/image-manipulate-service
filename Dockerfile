FROM php:8.2-fpm-alpine

RUN apk add -U --no-cache procps git openssh-client autoconf gcc make libc-dev libzip-dev bzip2-dev libxml2-dev libpng-dev g++ icu-dev
RUN docker-php-ext-install intl gd exif
RUN apk add -U --no-cache imagemagick imagemagick-dev imagemagick-libs musl libmagic \
 && pecl install imagick \
 && docker-php-ext-enable imagick
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY --chown=www-data ../app /app

WORKDIR /app

RUN composer install --no-interaction --no-progress --optimize-autoloader
CMD ["php-fpm"]

EXPOSE 9000
