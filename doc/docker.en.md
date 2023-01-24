# Docker
> If you need to use multiple containers, share the `/var/www/html/storage` directory between them by mounting directories to prevent each container refresh the ranking data

## Deployment
### Command
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

## Configure
By [environment](https://docs.docker.com/compose/compose-file/#environment) . all config items see [config.docker.php](../config.docker.php)

> Only the `local` image hosting is enabled by default (images are stored locally to the container). To use it, you must configure the `URL` item  
> 
> If the container cannot access this URL, the automatic update function does not available. In this case, it is recommended to set the environment variable `DISABLE_WEB_JOB=true` and refresh the ranking data by **Trigger updates proactively** at below

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