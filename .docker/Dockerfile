FROM ghcr.io/mokeyjay/pixiv-widget-image:main

COPY --chown=www-data:www-data . /var/www/html
COPY --chown=www-data:www-data .docker/config.php /var/www/html/config.php
COPY .docker/nginx-site.conf /etc/nginx/conf.d/default.conf
COPY .docker/start.sh /root/start.sh

# /etc/cron.d 下的任务需指定运行用户，这里用 www-data 以保证生成的文件权限与 php-fpm 一致
COPY .docker/crontab /etc/cron.d/pixiv-cron-job
RUN chmod 0644 /etc/cron.d/pixiv-cron-job

ENV TZ=Asia/Shanghai
RUN echo 'date.timezone = "Asia/Shanghai"' > $PHP_INI_DIR/conf.d/timezone.ini

LABEL Author="mokeyjay<i@mok.moe>"
LABEL Version="2026.10.22"
LABEL Description="Pixiv 每日排行榜小挂件"

CMD cron && \
    chmod +x /root/start.sh && \
    /root/start.sh
