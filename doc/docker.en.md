# docker Deployment

**command deployment**

```shell
docker run -d -p 80:80 --name=pixiv xxx
```

**docker-compose deployment**

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

read config from environment. all config field can see [config.php](../config.docker.php)

log file path: `/usr/share/nginx/html/storage/logs`

# Trigger updates proactively

see [Trigger updates proactively](./deploy.en.md)

```shell
docker exec pixiv php index.php -j=refresh
```

# Clear the log at regular intervals

see [Clear the log at regular intervals](./deploy.en.md)

```shell
docker exec pixiv php index.php -j=clear-log
```