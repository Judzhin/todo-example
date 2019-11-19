FROM php:7.2-fpm

RUN apt-get update && apt-get install -y libmcrypt-dev libpq-dev supervisor \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo_pgsql pgsql bcmath \
    && pecl install mcrypt \
    && docker-php-ext-enable mcrypt

COPY ./supervisord.conf /etc/supervisor
COPY ./supervisor/* /etc/supervisor/conf.d

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/supervisord.conf"]

WORKDIR /var/www