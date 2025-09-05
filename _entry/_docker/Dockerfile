FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    build-essential \
    libzip-dev \
    unzip \
    zip \
    vim \
    git \
    curl

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo pdo_mysql zip pcntl bcmath

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

COPY conf/php/xdebug.ini /usr/local/etc/php/conf.d/xdebug-dev.ini
COPY conf/php/custom-php.ini /usr/local/etc/php/conf.d/custom-php.ini

RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

WORKDIR /var/www/html

USER www



