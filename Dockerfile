FROM php:8.1-fpm

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpq-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo pdo_mysql zip

RUN echo "upload_max_filesize = 40M" > /usr/local/etc/php/conf.d/upload_max_filesize.ini \
    && echo "post_max_size = 40M" > /usr/local/etc/php/conf.d/post_max_size.ini

ENTRYPOINT ["sh", "./docker/entrypoint.sh"]
