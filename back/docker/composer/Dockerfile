FROM farruq1988/php8.2:myapp

RUN apt-get update \
    && apt-get install -y git zip unzip


RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/library
