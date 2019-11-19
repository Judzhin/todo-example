FROM php:7.2-fpm

RUN apt-get update && apt-get install -y libmcrypt-dev libpq-dev \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo_pgsql pgsql bcmath \
    && pecl install mcrypt \
    && docker-php-ext-enable mcrypt

ADD ./php.ini /usr/local/etc/php/conf.d/default.ini
WORKDIR /var/www