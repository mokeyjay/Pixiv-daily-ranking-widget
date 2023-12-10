<h1 align="center">🖼️ Pixiv 每日排行榜小挂件</h1>
<p align="center">
    <a href="https://github.com/mokeyjay/Pixiv-daily-ranking-widget/blob/master/README.en.md">English</a>
    <br><br>
    想要在你的网站页面中添加一个 <span style="font-weight: bold">Pixiv 每日排行榜</span> 的展示功能吗？现在，只需要一行代码即可实现！
    <a href="https://pixiv.mokeyjay.com/demo.html" target="_blank">在线预览</a>
</p>

## ✨ 特色
- 一行 `HTML` 代码即可调用，方便快捷
- 自适应宽高。推荐宽度 `240px`、高度 `380px` 或以上
- 点击图片可跳转到对应作品详情页
- 每日自动更新，无需人工干预
- 内置多图床支持、按需加载图片，极低资源消耗
- 提供 API 服务，含有排行榜更新日期、缩略图 url 及详情页 url 等

## 🤔 如何使用
将这行代码添加到网页上即可  
```html
<iframe src="https://pixiv.mokeyjay.com" style="width:240px; height:380px; border: 0"></iframe>
```

以 `Wordpress` 为例。首先进入后台，点击 外观 -> 小工具  
向右边适当的位置添加一个 **文本** 或 **自定义HTML** 小工具，内容填写上述代码即可  

[高级用法](https://github.com/mokeyjay/Pixiv-daily-ranking-widget/blob/master/doc/advance-usage.md)

## 🛠️ 如何部署
想要自己定制代码？嫌我提供的服务太慢？  
你也可以轻松拥有完全属于自己的小挂件！  
> 需要 Docker 或 PHP 版本 >= 5.6

[部署文档](https://github.com/mokeyjay/Pixiv-daily-ranking-widget/blob/master/doc/deploy.md)

## 🔌 API
[排行榜数据（已上传至图床）](https://pixiv.mokeyjay.com/?r=api/pixiv-json)（推荐）  
[排行榜数据（pixiv url）](https://pixiv.mokeyjay.com/?r=api/source-json)  

其中 `data` 为排行榜数据；`date` 为排行榜日期（可能是昨天或者前天，因为官方更新时间不一定）  
这两个接口都会自动根据请求头的 `Origin` 或者 `Referer` 返回对应跨域头。可供前端直接调用  

> `image` 和 `url` 两个键是为了兼容 4.x 及之前版本的用户，无需理会

## 🆙 升级指南
### 从 5.2 升级到 6.0
1. [下载源代码](https://github.com/mokeyjay/Pixiv-daily-ranking-widget/releases/latest)
2. 解压缩，将其中的 `app` 和 `index.php` 覆盖到线上环境
> **⚠️ 对于 Docker 方式部署的用户**
> - 请将环境变量名中所有 `-` 替换为 `_`
> - 镜像从 docker hub 迁移至 [ghcr.io](https://github.com/mokeyjay/Pixiv-daily-ranking-widget/pkgs/container/pixiv-daily-ranking-widget)

## 🌟 更新日志
### 新增
- `ranking_type` 配置项，现在可以选择拉取综合还是插画、漫画日榜啦~
- 图片预加载，优化网络环境较差时的体验
### 优化
- 完全重写了前端，更优雅的缓动效果
- 不再依赖 bootstrap，加载更快啦
- 改为使用官方 php、nginx 包
- 从本地存储获取图片时不再重复检查完整性
- `background_color` 配置项现在支持[更多种颜色](https://developer.mozilla.org/zh-CN/docs/Web/CSS/background-color)了
### 修复
- 部分环境变量在一些情况下无法被正常获取的问题
- 定时任务实际上是一小时执行一次，而非文档说的半小时一次
### 其他
- 由于不再依赖 bs，去除 `static_cdn` 配置项
- 删除已经失效的 `Pngcm`、`Tsesze` 图床
- Docker 镜像迁移至 [ghcr.io](https://github.com/mokeyjay/Pixiv-daily-ranking-widget/pkgs/container/pixiv-daily-ranking-widget)
- Docker 镜像时区默认为上海

[历史更新日志](https://github.com/mokeyjay/Pixiv-daily-ranking-widget/blob/master/doc/log.md)

## 👨‍💻 关于作者
常用 ID [mokeyjay](https://www.mokeyjay.com)，热爱 IT 与 ACG 的学渣