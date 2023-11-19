FROM ghcr.io/mokeyjay/pixiv-widget-image:main

COPY . /var/www/html
COPY docker/config.php /var/www/html/config.php
COPY docker/nginx-site.conf /etc/nginx/conf.d/default.conf
COPY docker/start.sh /root/start.sh

RUN echo "30 * * * * cd /var/www/html/ && /usr/local/bin/php index.php -j=refresh" >> /var/spool/cron/crontabs/root && \
    echo "30 1 * * * cd /var/www/html/ && /usr/local/bin/php index.php -j=clear-log" >> /var/spool/cron/crontabs/root

LABEL Author="mokeyjay<i@mokeyjay.com>"
LABEL Version="2023.11.20"
LABEL Description="Pixiv 每日排行榜小挂件"

CMD cron && \
    chmod +x /root/start.sh && \
    /root/start.sh
