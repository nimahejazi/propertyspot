FROM node:15.2.0 as builder
WORKDIR /app
COPY ./laravel/package.json ./
ARG SSH_KEY
RUN npm install
COPY ./laravel/resources ./resources
COPY ./laravel/webpack.mix.js ./
RUN npm run prod

FROM php:7.4-fpm
RUN apt-get update && \
    apt-get install -y nginx && \
    docker-php-ext-install bcmath && \
    docker-php-ext-install pdo_mysql && \
    apt-get install -y --no-install-recommends apt-utils && \
    apt-get install -y libmagickwand-dev libmagickcore-dev && \
    pecl install imagick && \
    docker-php-ext-enable imagick && \
    apt-get install -y libzip-dev zip && \
    docker-php-ext-install zip && \
    apt-get install libjpeg-dev libpng-dev && \
    docker-php-ext-configure gd --with-jpeg && \
    docker-php-ext-install gd && \
    apt-get clean
COPY ./laravel /var/www/html
COPY ./laravel/.env.prod /var/www/html/.env
RUN chown -R www-data:www-data /var/www/html
COPY ./site.conf /etc/nginx/conf.d/site.conf
COPY ./php-custom.ini /usr/local/etc/php/conf.d/php-custom.ini
COPY --from=builder app/public /var/www/html/public
CMD service nginx start && \
    php-fpm
