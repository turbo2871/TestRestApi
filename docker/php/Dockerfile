FROM php:7.4-fpm

RUN apt-get update -y

RUN apt-get install -y zlib1g-dev g++ librdkafka-dev git libicu-dev \
        zip libzip-dev librabbitmq-dev libmagickwand-dev imagemagick libmcrypt-dev \
        libpng-dev libjpeg-dev libwebp-dev libjpeg62-turbo-dev libfreetype6-dev \
    && pecl install mcrypt && docker-php-ext-enable mcrypt \
    && pecl install rdkafka && docker-php-ext-enable rdkafka \
    && docker-php-ext-install intl opcache pdo pdo_mysql gd \
    && docker-php-ext-configure zip && docker-php-ext-install zip \
    && pecl install imagick && docker-php-ext-enable imagick \
    && pecl install apcu && docker-php-ext-enable apcu \
    && pecl install amqp && docker-php-ext-enable amqp \
    && pecl install redis && docker-php-ext-enable redis \
    && pecl install mongodb && docker-php-ext-enable mongodb

RUN docker-php-ext-configure gd --with-jpeg --with-webp --with-freetype
RUN docker-php-ext-install gd

RUN apt-get install -y nodejs npm
RUN npm install -g n
RUN n stable
RUN n 14.16.1
RUN npm install -g npm@6.14.12
RUN npm install --global gulp-cli

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer self-update --2

RUN curl -sS https://get.symfony.com/cli/installer | bash

RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

WORKDIR /var/www/project

ARG user=${user}
ARG uid=${uid:-1000}

ARG ssh_prv_key
ARG ssh_pub_key
ARG ssh_known_hosts

RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

# Authorize SSH Host
RUN mkdir -p /home/$user/.ssh
RUN    chmod 0744 /home/$user/.ssh
RUN    echo "$ssh_known_hosts" > /home/$user/.ssh/known_hosts
RUN    chown -R $user:$user /home/$user/.ssh

# Add the keys and set permissions
RUN echo "$ssh_prv_key" > /home/$user/.ssh/id_rsa && \
    echo "$ssh_pub_key" > /home/$user/.ssh/id_rsa.pub && \
    chmod 600 /home/$user/.ssh/id_rsa && \
    chmod 644 /home/$user/.ssh/id_rsa.pub && \
    chown -R $user:$user /home/$user/.ssh

RUN echo "    IdentityFile ~/.ssh/id_rsa" >> /home/$user/.ssh/ssh_config && \
    chown -R $user:$user /home/$user/.ssh

USER $user

CMD [ "php-fpm", "-F" ]
