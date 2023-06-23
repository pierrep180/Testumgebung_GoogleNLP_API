FROM debian:bullseye-slim

RUN apt-get update && apt-get -y install \
    vim \
    wget \
    procps \
    iputils-ping \
    screen \
    aptitude \
    rsync \
    nginx

RUN apt-get update && apt-get -y install \
    php7.4 \
    php7.4-cli \
    php7.4-curl \
    php7.4-fpm \
    php7.4-gd \
    php7.4-json \
    php7.4-mbstring \
    php7.4-mysql \
    php7.4-simplexml \
    php7.4-xml \
    php7.4-memcached \
    php7.4-readline \
    php7.4-opcache \
    php7.4-xdebug \
    php7.4-soap \
    php-redis \
    php-imagick \
    php-dev \
    php-pear \
    libz-dev \
    composer

RUN pecl install grpc

RUN mkdir /run/php

COPY ./nginx/default.conf /etc/nginx/sites-available/default
COPY ./php/php.ini /etc/php/7.4/cli/php.ini
COPY ./php/php.ini /etc/php/7.4/fpm/php.ini
COPY ./php/pool.conf /etc/php/7.4/fpm/pool.d/www.conf

COPY ./entrypoint.sh /

VOLUME ["/var/www/html", "/var/lib/mysql"]

ENTRYPOINT ["/bin/bash", "/entrypoint.sh"]

EXPOSE 80 443 3306 6379

CMD ["/bin/bash"]

