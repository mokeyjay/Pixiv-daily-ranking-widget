FROM richarvey/nginx-php-fpm:2.1.2

COPY ./ /var/www/html
COPY ./config.docker.php /var/www/html/config.php
