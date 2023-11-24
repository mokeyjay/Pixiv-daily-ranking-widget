# Docker
> 如需启用多个容器，请通过挂载目录的方式在它们之间共享 `/var/www/html/storage` 目录，避免每个容器各自更新排行榜数据，造成性能浪费

## 部署
### 命令行
```shell
docker run -d -p 80:80 --name=pixiv -e URL=http://localhost/ ghcr.io/mokeyjay/pixiv-daily-ranking-widget
```

### Docker compose
```yaml
version: '3.1'

services:
  pixiv:
    image: ghcr.io/mokeyjay/pixiv-daily-ranking-widget
    container_name: pixiv
    restart: always
    environment:
      URL: http://localhost/
    ports:
      - "80:80"
```

> `URL` 是指向这个容器的访问地址，支持路径，必须以 `/` 结尾

## 配置
通过 [环境变量](https://docs.docker.com/compose/compose-file/#environment) 进行配置。所有配置项见 [config.docker.php](../.docker/config.php)

> 默认只启用了 `local` 图床（即图片存储在容器本地）。使用它时，必须配置 `URL` 项  
> 
> 与本地部署不同，Docker 镜像内置了自动更新排行榜数据的定时任务，因此 `DISABLE_WEB_JOB` 默认为 `true`，即不通过 web 访问触发更新

> 日志路径：`/var/www/html/storage/logs`

## 任务
### 主动触发更新
> 通常情况下，排行榜数据会每半小时检测一次更新，无需主动触发  
> 首次部署时，可以手动触发一次更新，或等待半小时自动更新

```shell
docker exec pixiv php index.php -j=refresh
```
详见 [主动触发更新](https://github.com/mokeyjay/Pixiv-daily-ranking-widget/blob/master/doc/deploy.md)

### 清除日志
```shell
docker exec pixiv php index.php -j=clear-log
```
详见 [清除日志](https://github.com/mokeyjay/Pixiv-daily-ranking-widget/blob/master/doc/deploy.md)