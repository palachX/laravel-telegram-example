FROM php:8.3.23-fpm-alpine3.22

ARG UID
ARG GID

RUN addgroup -S app -g $GID && adduser -S app -G app -u $UID

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --2.7.2 \
    && php -r "unlink('composer-setup.php');" \
    && mv composer.phar /usr/local/bin/composer

RUN apk --update --no-cache add  \
    $PHPIZE_DEPS \
    git \
    libpq-dev \
    linux-headers \
    && pecl install redis xdebug\
    && docker-php-ext-install pdo_pgsql sockets \
    && docker-php-ext-enable redis xdebug\
    && apk del $PHPIZE_DEPS

USER app

WORKDIR /opt/app
