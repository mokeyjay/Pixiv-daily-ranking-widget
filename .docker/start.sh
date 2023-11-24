#!/bin/bash

echo '' > /etc/nginx/sites-enabled/default

printenv > /var/www/html/.docker/env.sh

chown -R www-data:www-data /var/www/html

php-fpm -D
nginx -g 'daemon off;'