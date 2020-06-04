FROM php:7.4.6-fpm-alpine3.11

#install pdo and pdo mysq extension for php
RUN docker-php-ext-install pdo pdo_mysql

# install composer
RUN wget https://raw.githubusercontent.com/composer/getcomposer.org/76a7060ccb93902cd7576b67264ad91c8a2700e2/web/installer -O - -q | php -- --quiet --install-dir /usr/local/bin/

# necessary to install/compile swoole
RUN apk add autoconf gcc g++ make

RUN pecl install swoole

#enable swoole for php
RUN touch /usr/local/etc/php/conf.d/swoole.ini && \
    echo 'extension=swoole.so' > /usr/local/etc/php/conf.d/swoole.ini

COPY ./ /var/www/

WORKDIR /var/www

#installing proyect's dependencies
RUN composer.phar install

#by default swoole only runs on localhost, so we change it
ENV SWOOLE_HTTP_HOST=0.0.0.0

#enable access log
ENV SWOOLE_HTTP_ACCESS_LOG=true

#expose default swoole http port
EXPOSE 1215

#run the migrations, if there is any and start server
ENTRYPOINT php artisan migrate --force && php artisan swoole:http start