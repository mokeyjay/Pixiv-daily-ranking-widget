FROM php:8.0.30-fpm-bullseye

ADD docker/sources.list /etc/apt/

RUN apt update && \
    apt install -y nginx cron

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions gd

COPY . /var/www/html
COPY docker/config.php /var/www/html/config.php
COPY docker/nginx-site.conf /etc/nginx/conf.d/default.conf
COPY docker/start.sh /root/start.sh

RUN echo "30 * * * * cd /var/www/html/ && /usr/local/bin/php index.php -j=refresh" >> /var/spool/cron/crontabs/root && \
    echo "30 1 * * * cd /var/www/html/ && /usr/local/bin/php index.php -j=clear-log" >> /var/spool/cron/crontabs/root

LABEL Author="mokeyjay<i@mokeyjay.com>"
LABEL Version="2023.09.12"
LABEL Description="Pixiv 每日排行榜小挂件"

CMD cron && \
    chmod +x /root/start.sh && \
    /root/start.sh
