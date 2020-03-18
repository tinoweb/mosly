FROM php:7.4-fpm

RUN apt-get update && apt-get install -y \
      libicu-dev \
      libpq-dev \
      libxml2-dev \
      zlib1g-dev \
      libpng-dev \
      libjpeg-dev \
      libonig-dev \
      libzip-dev \
      libfreetype6-dev \
      curl\
      pkg-config \
      patch\
    && rm -r /var/lib/apt/lists/* \
    && docker-php-ext-install \
      intl \
      mbstring \
      pcntl \
      pdo \
      pdo_mysql \
      opcache \
      dom \
      zip


# install cron
RUN apt-get update && apt-get -y install rsyslog

# Change uid and gid of apache to docker user uid/gid
RUN usermod -u 1000 www-data && groupmod -g 1000 www-data

WORKDIR /var/www/html