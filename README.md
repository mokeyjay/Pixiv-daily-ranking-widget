<h1 align="center">🖼️ Pixiv 每日排行榜小挂件</h1>
<p align="center">
    <a href="README.en.md">English</a>
    <br><br>
    想要在你的网站页面中添加一个 <span style="font-weight: bold">Pixiv 每日排行榜</span> 的展示功能吗？现在，只需要一行代码即可实现！
    <a href="https://cloud.mokeyjay.com/pixiv/demo.html" target="_blank"></a>
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
<iframe src="https://cloud.mokeyjay.com/pixiv" style="width:240px; height:380px; border: 0"></iframe>
```

以 `Wordpress` 为例。首先进入后台，点击 外观 -> 小工具  
向右边适当的位置添加一个 **文本** 或 **自定义HTML** 小工具，内容填写上述代码即可  

[高级用法](doc/advance-usage.md)

## 🛠️ 如何部署
想要自己定制代码？嫌我提供的服务太慢？  
你也可以轻松拥有完全属于自己的小挂件！  
> 需要 PHP 版本 >= 5.6

[部署文档](doc/deploy.md)

## 🪄 API
[排行榜数据（已上传至图床）](https://cloud.mokeyjay.com/pixiv/?r=api/pixiv-json)（推荐）  
[排行榜数据（官方 url）](https://cloud.mokeyjay.com/pixiv/?r=api/source-json)  

其中 `data` 为排行榜数据；`date` 为排行榜日期（可能是昨天或者前天，因为官方更新时间不一定）  
这两个接口都会自动根据请求头的 `Origin` 或者 `Referer` 返回对应跨域头。可供前端直接调用  

> `image` 和 `url` 两个键是为了兼容 4.x 及之前版本的用户，无需理会

## 🌟 更新日志
### 新增
- 支持跨域请求的数据接口
- 58、京东、小宇宙图床
- clear-log 任务用于清除历史日志文件
### 优化
- 左右翻页箭头现在会自动隐藏了
- 鼠标悬浮时显示作品标题及作者名称
- 升级前端依赖包至最新、减少依赖
- 完善日志记录功能
- 丰富接口数据以适应更多场景
- 将百度统计更换为谷歌统计
- 更新默认 UA
### 修复
- 修复部分情况下无法正确获取项目 url 的问题
### 其他
- 去掉失效的阿里巴巴图床

[历史更新日志](doc/log.md)

## 👨‍💻 关于作者
常用 ID [mokeyjay](https://www.mokeyjay.com)，热爱 IT 与 ACG 的学渣