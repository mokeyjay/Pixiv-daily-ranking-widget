FROM wyveo/nginx-php-fpm:php81

COPY ./ /usr/share/nginx/html
COPY ./config.docker.php /usr/share/nginx/html/config.php

WORKDIR /usr/share/nginx/html

