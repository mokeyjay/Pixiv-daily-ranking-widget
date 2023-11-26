FROM ghcr.io/mokeyjay/pixiv-widget-image:main

COPY . /var/www/html
COPY .docker/config.php /var/www/html/config.php
COPY .docker/nginx-site.conf /etc/nginx/conf.d/default.conf
COPY .docker/start.sh /root/start.sh

COPY .docker/crontab /etc/cron.d/pixiv-cron-job
RUN chmod 0644 /etc/cron.d/pixiv-cron-job && \
    crontab /etc/cron.d/pixiv-cron-job

ENV TZ=Asia/Shanghai

LABEL Author="mokeyjay<i@mokeyjay.com>"
LABEL Version="2023.11.20"
LABEL Description="Pixiv 每日排行榜小挂件"

CMD cron && \
    chmod +x /root/start.sh && \
    /root/start.sh
