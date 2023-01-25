FROM richarvey/nginx-php-fpm:2.1.2

RUN rm -rf /var/www/html/*

COPY . /var/www/html
COPY docker/config.php /var/www/html/config.php
COPY docker/nginx-site.conf /etc/nginx/sites-available/default.conf

RUN echo "30 * * * * cd /var/www/html/ && /usr/local/bin/php index.php -j=refresh" >> /var/spool/cron/crontabs/root \
  && echo "30 1 * * * cd /var/www/html/ && /usr/local/bin/php index.php -j=clear-log" >> /var/spool/cron/crontabs/root

CMD crond && /start.sh
