# Docker
> If you need to use multiple containers, share the `/var/www/html/storage` directory between them by mounting directories to prevent each container refresh the ranking data

## Deployment
### Command
```shell
docker run -d -p 80:80 --name=pixiv -e URL=http://localhost/ mokeyjay/pixiv-daily-ranking-widget
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
      URL: http://localhost/
    ports:
      - "80:80"
```

> `URL` is the access url to the container, supports path, and must end with `/`

## Configure
By [environment](https://docs.docker.com/compose/compose-file/#environment) . all config items see [config.docker.php](../docker/config.php)

> Only the `local` image hosting is enabled by default (images are stored locally to the container). To use it, you must configure the `URL` item  
> 
> Different from local deployment, Docker image has built-in scheduling tasks that automatically update ranking data. Therefore, the default value of `DISABLE_WEB_JOB` is `true`, which means that updates are not triggered through web access

> logs path: `/var/www/html/storage/logs`

## Jobs
### Trigger updates proactively
> In general, ranking data is detected for updates every half hour without being actively triggered

```shell
docker exec pixiv php index.php -j=refresh
```
See [Trigger updates proactively](https://github.com/mokeyjay/Pixiv-daily-ranking-widget/blob/master/doc/deploy.en.md)

### Clear the logs
```shell
docker exec pixiv php index.php -j=clear-log
```
See [Clear the logs](https://github.com/mokeyjay/Pixiv-daily-ranking-widget/blob/master/doc/deploy.en.md)