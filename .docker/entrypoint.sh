#!/usr/bin/env bash

echo "Starting php-fpm..."
/etc/init.d/php7.4-fpm restart

echo "Starting nginx..."
/etc/init.d/nginx restart

exec "$@"