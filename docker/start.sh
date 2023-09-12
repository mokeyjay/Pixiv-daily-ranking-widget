#!/bin/bash

echo '' > /etc/nginx/sites-enabled/default

chown -R www-data:www-data /var/www/html

php-fpm -D
nginx -g 'daemon off;'