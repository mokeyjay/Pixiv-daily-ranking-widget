#!/bin/bash

echo '' > /etc/nginx/sites-enabled/default

# 将环境变量保存起来，免得 crontab 读不到
# 部分环境变量值含有空格，得用双引号包起来，不然 source 时会报错
printenv | awk -F= -v OFS== '{ if ($2 ~ /[[:space:]]/) $2="\""$2"\""; print }' > /var/www/html/.docker/env.sh

chown -R www-data:www-data /var/www/html

php-fpm -D
nginx -g 'daemon off;'