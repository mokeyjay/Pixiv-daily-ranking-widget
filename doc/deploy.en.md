# Deployment
## Docker
```shell
docker run -d -p 80:80 --name=pixiv -e URL=http://localhost/ ghcr.io/mokeyjay/pixiv-daily-ranking-widget
```
See [Docker](https://github.com/mokeyjay/Pixiv-daily-ranking-widget/blob/master/doc/docker.en.md)
## Local
- [Download source code](https://github.com/mokeyjay/Pixiv-daily-ranking-widget/releases/latest)
- Unzip under your the web directory
- Edit `config.php` with a **professional** editor (e.g. `Visual Studio Code`, `Sublime`, etc., notepad is not allowed) and modify the configuration according to your own needs
- Grant write permission to `storage` directory

## Trigger updates proactively
By default, this widget will check if the ranking data is out of date every time it is accessed and make an additional request to trigger an automatic update if needed. In most cases, this operation is not required.

However, in some cases (e.g. PHP timeouts are not long enough, server performance is too poor), this web-triggered update will be interrupted before it finishes. This will interrupt the update and never let it finish, and also keeps consuming unnecessary performance and bandwidth.  
If you encounter the problem above, you can turn off web-triggered updates (set `disable_web_job` to `false` in `config.php`)  
Then trigger update proactively via cli, e.g. `php index.php -j=refresh`

> You can use a tool like `crontab` to automatically trigger updates on a regular basis. For example `*/30 * * * * php /path/to/pixiv/index.php -j=refresh`  
> It means run update every 30 minutes. The program will automatically determine whether it really needs to be updated, and it won't waste performance

## Clear the logs
Opening the log (set `log_level` to `['DEBUG', 'ERROR']` in `config.php`) will log this widget's running info.  
When giving me feedback on a problem, it is usually recommended to send me the logs and configuration file as a zip package (please do not post the configuration file to the public)  
If you are going to keep the log enabled for a long time and are worried about it taking up too much hard drive, you can delete the logs over 7 days with `php index.php -j=clear-log`

> Add the parameter `-n=3` to delete logs that are 3 days old

> You can use a tool like `crontab` to delete automatically on a regular basis. For example `30 1 * * * php /path/to/pixiv/index.php -j=clear-log`