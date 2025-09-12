FROM php:8.2-fpm AS php

WORKDIR /var/www/html

RUN apt-get update \
    && apt-get install -y --no-install-recommends zlib1g-dev g++ git libicu-dev zip libzip-dev nano libxml2-dev ca-certificates wget \
    && docker-php-ext-install intl opcache pdo pdo_mysql dom xml \
    && pecl install apcu xdebug \
    && docker-php-ext-enable apcu xdebug \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip \
    && rm -rf /var/lib/apt/lists/*

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN curl -sS https://get.symfony.com/cli/installer | bash -s -- --install-dir=/usr/local/bin

FROM php AS php_supervisor

RUN apt-get update \
    && apt-get install -y --no-install-recommends supervisor \
    && docker-php-ext-install pcntl \
    && rm -rf /var/lib/apt/lists/*


RUN mkdir -p /etc/supervisor/conf.d /var/log/supervisor

CMD ["php-fpm"]