FROM farruq1988/php8.2-fpm:macm1


RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/library

RUN set -eux; \
    apk update; \
    apk add --no-cache \
        imagemagick-dev; \
    if ! php -m | grep -q imagick; then \
        pecl install imagick; \
        docker-php-ext-enable imagick; \
    fi;

