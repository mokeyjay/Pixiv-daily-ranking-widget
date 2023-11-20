# 部署
## Docker
```shell
docker run -d -p 80:80 --name=pixiv -e URL=http://localhost/ ghcr.io/mokeyjay/pixiv-daily-ranking-widget
```
详见 [Docker](https://github.com/mokeyjay/Pixiv-daily-ranking-widget/blob/master/doc/docker.md)
## 本地部署
- [下载源代码](https://github.com/mokeyjay/Pixiv-daily-ranking-widget/releases/latest)
- 解压缩到 web 目录下
- 使用专业编辑器（例如 `Visual Studio Code`、`Sublime` 等，禁止使用记事本）编辑 `config.php`，根据实际情况修改相应配置
> 由于 Pixiv 已经被墙，如果你想要将此项目部署在中国大陆境内，可能需要配置 `proxy` 配置项

- 给予 `storage` 目录写入权限

## 主动触发更新
默认情况下，此挂件每次被访问都会检查排行榜数据是否已过期，并在需要时额外发起一次请求来触发自动更新。通常不需要干预  

但在有些情况下（例如 PHP 超时时间设的不够长、服务器性能较低），这种通过 web 方式触发的更新还没执行完就被中断，不仅导致更新始终无法完成，还会一直浪费性能及带宽  
如果你遇到同样的问题，可以关闭 web 方式触发更新（将 `config.php` 中的 `disable_web_job` 设为 `false`）  
然后通过 cli 方式主动触发，例如 `php index.php -j=refresh`  

> 你可以使用 `crontab` 之类的工具来定时自动触发更新。例如 `*/30 * * * * php /path/to/pixiv/index.php -j=refresh`  
> 表示每 30 分钟执行一次更新。程序会自动判断是否真的需要更新，不会浪费性能

## 清除日志
打开日志（`config.php` 中的 `log_level` 设为 `['DEBUG', 'ERROR']`）可以将此挂件的运行信息记录下来  
向我反馈问题时，通常也建议将日志和配置文件打包发送给我（请勿将配置文件发布到公开场合）  
如果你准备长期打开日志又担心它占用过多的硬盘，可以通过 `php index.php -j=clear-log` 来删除 7 天前的日志  
> 添加参数 `-n=3` 即删除 3 天前的日志

> 你可以使用 `crontab` 之类的工具来定时自动删除。例如 `30 1 * * * php /path/to/pixiv/index.php -j=clear-log`