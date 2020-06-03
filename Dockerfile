FROM php:7.4.6-fpm-alpine3.11

RUN docker-php-ext-install pdo pdo_mysql

RUN wget https://raw.githubusercontent.com/composer/getcomposer.org/76a7060ccb93902cd7576b67264ad91c8a2700e2/web/installer -O - -q | php -- --quiet --install-dir /usr/local/bin/


RUN apk add autoconf gcc g++ make

RUN pecl install swoole

RUN touch /usr/local/etc/php/conf.d/swoole.ini && \
    echo 'extension=swoole.so' > /usr/local/etc/php/conf.d/swoole.ini

COPY ./ /var/www/

WORKDIR /var/www

RUN composer.phar install

ENV SWOOLE_HTTP_HOST=0.0.0.0

EXPOSE 1215

ENTRYPOINT php artisan migrate && php artisan swoole:http start