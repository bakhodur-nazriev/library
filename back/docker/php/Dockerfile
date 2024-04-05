FROM farruq1988/php8.2-fpm:macm1


RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/library
# Install imagick extension
#RUN set -eux; \
#    apk update; \
#    apk add --no-cache \
#        imagemagick-dev; \
#    pecl install imagick; \
#    docker-php-ext-enable imagick;
