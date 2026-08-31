FROM node:15.8.0 as builder
WORKDIR /app
COPY ./laravel/package.json ./
COPY ./laravel/package-lock.json ./
ARG SSH_KEY
# Authorize SSH Host
RUN mkdir -p /root/.ssh && \
    chmod 0700 /root/.ssh && \
    ssh-keyscan github.com > /root/.ssh/known_hosts

# Add the key and set permissions
RUN echo "$SSH_KEY" > /root/.ssh/id_rsa && \
    chmod 600 /root/.ssh/id_rsa

RUN npm ci
COPY ./laravel/resources ./resources
COPY ./laravel/webpack.mix.js ./
COPY ./laravel/babel.config.json ./
COPY ./laravel/tsconfig.json ./
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
COPY ./site.conf /etc/nginx/conf.d/site.conf
COPY ./php-custom.ini /usr/local/etc/php/conf.d/php-custom.ini
COPY --from=builder app/public /var/www/html/public
RUN chown -R www-data:www-data /var/www/html
CMD service nginx start && \
    php-fpm
