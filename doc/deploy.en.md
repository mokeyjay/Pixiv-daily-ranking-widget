# Deployment
- [Download source code](https://github.com/mokeyjay/Pixiv-daily-ranking-widget/releases/latest)
- Unzip to the web directory
- Edit `config.php` with a professional editor (e.g. `Visual Studio Code`, `Sublime`, etc., notepad is not allowed) and modify the configuration accordingly
- Give write permission to `storage` directory

## Proactively trigger updates
By default, this widget will check if the ranking data is out of date every time it is accessed and make an additional request to trigger an automatic update if needed. Usually no intervention is required  

However, in some cases (e.g. PHP timeouts are not long enough, server performance is low), this web-triggered update is interrupted before it finishes, which not only causes the update to never finish, but also keeps wasting performance and bandwidth.  
If you encounter the same problem, you can turn off web-triggered updates (set `disable_web_job` to `false` in `config.php`)  
Then trigger update proactively via cli, e.g. `php index.php -j=refresh`

> You can use a tool like `crontab` to automatically trigger updates on a regular basis. For example `*/30 * * * * php /path/to/pixiv/index.php -j=refresh`  
> It means run update every 30 minutes. The program will automatically determine whether it really needs to be updated, and it won't waste performance

## Clear the log at regular intervals
Opening the log (set `log_level` to `['DEBUG', 'ERROR']` in `config.php`) will log this widget's running info  
When giving me feedback on a problem, it is also usually recommended to send me the logs and configuration file as a package (please do not post the configuration file to the public)  
If you are going to keep the log open for a long time and are worried about it taking up too much hard drive, you can delete the 7 day old log with `php index.php -j=clear-log`
> Add the parameter `-n=3` to delete logs that are 3 days old

> You can use a tool like `crontab` to delete automatically on a regular basis. For example `30 1 * * * php /path/to/pixiv/index.php -j=clear-log`