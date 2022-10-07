# docker部署

**命令行部署**

```shell
docker run -d -p 80:80 --name=pixiv xxx
```

**docker-compose 部署**

```yaml
version: '3.1'

services:
  pixiv:
    image: xxx
    container_name: pixiv
    restart: always
    environment:
      LOG_LEVEL: DEBUG,ERROR
      DISABLE_WEB_JOB: false
```

通过环境变量进行配置. 所有可配置详见[配置文件](../config.docker.php)

项目日志路径: `/usr/share/nginx/html/storage/logs`
 

# 主动触发更新

详见 [主动触发更新](./deploy.md)

```shell
docker exec pixiv php index.php -j=refresh
```

# 清除日志

详见 [主动触发更新](./deploy.md)

```shell
docker exec pixiv php index.php -j=clear-log
```