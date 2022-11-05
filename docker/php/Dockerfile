FROM php:7.4-fpm

RUN apt-get update -y

RUN apt-get install -y zlib1g-dev g++ librdkafka-dev git libicu-dev \
        zip libzip-dev librabbitmq-dev libmagickwand-dev imagemagick libmcrypt-dev \
        libpng-dev libjpeg-dev libwebp-dev libjpeg62-turbo-dev libfreetype6-dev \
    && pecl install mcrypt && docker-php-ext-enable mcrypt \
    && docker-php-ext-install intl opcache pdo pdo_mysql gd \
    && docker-php-ext-configure zip && docker-php-ext-install zip \
    && pecl install imagick && docker-php-ext-enable imagick \
    && pecl install apcu && docker-php-ext-enable apcu \
    && pecl install amqp && docker-php-ext-enable amqp \
    && pecl install redis && docker-php-ext-enable redis

RUN docker-php-ext-configure gd --with-jpeg --with-webp --with-freetype
RUN docker-php-ext-install gd

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer self-update --2

RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

WORKDIR /var/www/project

ARG user=${user}
ARG uid=${uid:-1000}

RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

USER $user

CMD [ "php-fpm", "-F" ]
