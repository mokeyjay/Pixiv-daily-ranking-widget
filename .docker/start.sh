#!/bin/bash

echo '' > /etc/nginx/sites-enabled/default

cd /var/www/html
# 将环境变量保存起来，免得 crontab 读不到
env=("URL" "BACKGROUND_COLOR" "LIMIT" "SERVICE" "LOG_LEVEL" "PROXY" "CLEAR_OVERDUE" "COMPRESS" "IMAGE_HOSTING" "IMAGE_HOSTING_EXTEND_TIETUKU_TOKEN" "IMAGE_HOSTING_EXTEND_SMMS_TOKEN" "IMAGE_HOSTING_EXTEND_RIYUGO_URL" "IMAGE_HOSTING_EXTEND_RIYUGO_UPLOAD_PATH" "IMAGE_HOSTING_EXTEND_RIYUGO_UNIQUE_ID" "IMAGE_HOSTING_EXTEND_RIYUGO_TOKEN" "DISABLE_WEB_JOB" "HEADER_SCRIPT" "RANKING_TYPE")
file=".env"
> $file
for var in "${env[@]}"
do
  # 部分环境变量值含有空格、换行，得用双引号包起来，不然 source 时会报错
  value="${!var}"
  value="${value//\"/\\\"}"
  echo "$var=\"$value\"" >> $file
done

chown -R www-data:www-data /var/www/html

php-fpm -D
nginx -g 'daemon off;'