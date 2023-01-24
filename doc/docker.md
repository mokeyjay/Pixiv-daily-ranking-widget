# Docker
> 如需启用多个容器，请通过挂载目录的方式在它们之间共享 `/var/www/html/storage` 目录，避免每个容器各自为政、重复更新排行榜数据

## 部署
### 命令行
```shell
docker run -d -p 80:80 --name=pixiv mokeyjay/pixiv-daily-ranking-widget
```

### Docker compose
```yaml
version: '3.1'

services:
  pixiv:
    image: mokeyjay/pixiv-daily-ranking-widget
    container_name: pixiv
    restart: always
    environment:
      DISABLE_WEB_JOB: false
    ports:
      - "80:80"
```

## 配置
通过 [环境变量](https://docs.docker.com/compose/compose-file/#environment) 进行配置。所有配置项见 [config.docker.php](https://github.com/mokeyjay/Pixiv-daily-ranking-widget/blob/master/doc/config.docker.php)

> 默认只启用了 `local` 图床（即图片存储在容器本地）。使用它时，必须配置 `URL` 项  
> 
> 如果容器无法访问此 URL，则自动更新功能无法正常运作。此时建议设置环境变量 `DISABLE_WEB_JOB=true` 并通过下方的 **主动触发更新** 来刷新排行榜数据

> 日志路径：`/var/www/html/storage/logs`

## 任务
### 主动触发更新
```shell
docker exec pixiv php index.php -j=refresh
```
详见 [主动触发更新](https://github.com/mokeyjay/Pixiv-daily-ranking-widget/blob/master/doc/deploy.md)

### 清除日志
```shell
docker exec pixiv php index.php -j=clear-log
```
详见 [清除日志](https://github.com/mokeyjay/Pixiv-daily-ranking-widget/blob/master/doc/deploy.md)