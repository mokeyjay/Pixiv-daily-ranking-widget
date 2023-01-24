# Docker
## Deployment
### Command
```shell
docker run -d -p 80:80 --name=pixiv xxx
```

### Docker compose
```yaml
version: '3.1'

services:
  pixiv:
    image: xxx
    container_name: pixiv
    restart: always
    environment:
      DISABLE_WEB_JOB: false
    ports:
      - "80:80"
```

## Configure
By [environment](https://docs.docker.com/compose/compose-file/#environment) . all config items see [config.docker.php](../config.docker.php)

> logs path: `/var/www/html/storage/logs`

## Jobs
### Trigger updates proactively
```shell
docker exec pixiv php index.php -j=refresh
```
See [Trigger updates proactively](https://github.com/mokeyjay/Pixiv-daily-ranking-widget/blob/master/doc/deploy.en.md)

### Clear the logs
```shell
docker exec pixiv php index.php -j=clear-log
```
See [Clear the logs](https://github.com/mokeyjay/Pixiv-daily-ranking-widget/blob/master/doc/deploy.en.md)