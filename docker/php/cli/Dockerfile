FROM php:7.4-cli

# 1. Встановлюємо системні залежності та очищуємо кеш apt одним шаром
RUN apt-get update && apt-get install -y --no-install-recommends \
    libmcrypt-dev \
    zlib1g-dev \
    libpq-dev \
    libzip-dev \
    unzip \
    wget \
    ffmpeg \
    && rm -rf /var/lib/apt/lists/*

# 2. Встановлюємо PHP розширення
RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install -j$(nproc) \
        pdo_pgsql \
        pgsql \
        zip \
        bcmath \
        pcntl \
        sockets

# 3. Встановлюємо PECL розширення (Xdebug 3.1.6 - остання для PHP 7.4)
RUN pecl install xdebug-3.1.6 mcrypt \
    && docker-php-ext-enable xdebug mcrypt

# 4. Налаштування Xdebug (оновлено до версії 3.x)
RUN { \
    echo "xdebug.mode=debug"; \
    echo "xdebug.start_with_request=yes"; \
    echo "xdebug.client_host=host.docker.internal"; \
    echo "xdebug.client_port=9003"; \
    echo "xdebug.idekey=mertblog.net"; \
    } > /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# 5. Встановлення Composer (через копіювання з офіційного образу — це швидше та надійніше)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www