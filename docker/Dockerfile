FROM php:8.4-cli

RUN apt-get update && apt-get install -y \
    git \
    unzip

## Install composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer && \
    php -r "unlink('composer-setup.php');"

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

COPY ./docker/xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

RUN cp /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini
RUN echo "error_reporting = E_ALL" >> /usr/local/etc/php/php.ini

COPY ./docker/xdebug-toggle.sh /usr/local/bin/xdebug
RUN chmod +x /usr/local/bin/xdebug

RUN xdebug off

WORKDIR /app

COPY ./composer.json /app/composer.json
COPY ./composer.lock /app/composer.lock

RUN composer install

CMD ["tail", "-f", "/dev/null"]
