FROM php:7.4.6-fpm-alpine3.11

RUN docker-php-ext-install pdo pdo_mysql

RUN wget https://raw.githubusercontent.com/composer/getcomposer.org/76a7060ccb93902cd7576b67264ad91c8a2700e2/web/installer -O - -q | php -- --quiet --install-dir /usr/local/bin/