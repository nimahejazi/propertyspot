FROM php:7.4-fpm
RUN apt-get update && \
    apt-get install -y nginx && \
    docker-php-ext-install bcmath && \
    docker-php-ext-install pdo_mysql && \
    apt-get install -y --no-install-recommends apt-utils && \
    apt-get install -y libmagickwand-dev libmagickcore-dev && \
    pecl install imagick && \
    docker-php-ext-enable imagick
CMD service nginx start && \
    php-fpm
