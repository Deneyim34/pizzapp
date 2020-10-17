FROM php:7.2-fpm-alpine

RUN docker-php-ext-install pdo pdo_mysql


RUN apk upgrade --update && apk add \
            freetype-dev \
            libjpeg-turbo-dev \
            libmcrypt-dev \
            libpng-dev \
            && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
            && docker-php-ext-install -j$(nproc) gd

#RUN docker-php-ext-install mbstring

#RUN docker-php-ext-install zip

#RUN docker-php-ext-install gd