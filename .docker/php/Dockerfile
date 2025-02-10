FROM php:8.2-fpm

WORKDIR /var/www/html

RUN apt update \
    && apt install -y zlib1g-dev g++ git libicu-dev zip libzip-dev zip nano libxml2-dev \
    && docker-php-ext-install intl opcache pdo pdo_mysql dom xml \
    && pecl install apcu xdebug \
    && docker-php-ext-enable apcu xdebug \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN curl -sS https://get.symfony.com/cli/installer | bash


#RUN mv /root/.symfony5/bin/symfony /usr/local/bin/symfony