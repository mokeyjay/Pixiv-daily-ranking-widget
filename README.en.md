<h1 align="center">🖼️ Pixiv Daily Ranking Widget</h1>
<p align="center">
    <a href="README.md">中文</a>
    <br><br>
    Want to add a <span style="font-weight: bold">Pixiv Daily Ranking Widget</span> to your website? Now, it only takes a single line of code to achieve it!
    <a href="https://cloud.mokeyjay.com/pixiv/demo.html" target="_blank">DEMO</a>
</p>

## ✨ Features
- Easy to call with one line of `HTML` code
- Adaptive width and height. Recommended width `240px`, height `380px` or more
- Click on the image to jump to the corresponding work details page
- Daily automatic update, no manual intervention
- Built-in multi image-hosting support, on-demand image loading, extremely low resource consumption
- Provide API service, including ranking update date, thumbnail url and detail page url, etc.

## 🤔 How to use
Just add this line of code to the page
```html
<iframe src="https://cloud.mokeyjay.com/pixiv" style="width:240px; height:380px; border: 0"></iframe>
```

Take `Wordpress` as an example. First go to the backend, click Appearance -> Widgets  
Add a **Text** or **Custom HTML** widget to the right in the appropriate place and fill in the above code

[Advance Usage](doc/advance-usage.en.md)

## 🛠️ How to deploy
Want to customize the code yourself? Think my service too slow?
You can also easily have your own widget!
> Requires PHP version >= 5.6

[Deployment Documentation](doc/deploy.en.md)

## 🪄 API
[Ranking data (already uploaded to the image-hosting)](https://cloud.mokeyjay.com/pixiv/?r=api/pixiv-json) (recommended)  
[Ranking data (official url)](https://cloud.mokeyjay.com/pixiv/?r=api/source-json)

`data` is the ranking data; `date` is the ranking date (maybe yesterday or the day before yesterday, because the official update time is not necessarily)  
Both api automatically return the corresponding cross-domain header based on the `Origin` or `Referer` in the request header. They can be called directly by the front-end

> The `image` and `url` keys are for compatibility with users of 4.x and earlier versions, you can ignore them

## 🌟 Changelog
### Added
- new api (Support cross-domain)
- 58, jd, xiaoyuzhou image-hosting
- clear-log job for clearing history log files
### Optimization
- Left and right page flip arrows are now automatically hidden
- Show work title and author name when hovering
- Upgrade front-end dependency package to the latest, reduce dependency
- Improve logging function
- Enrich interface data to fit more scenarios
- Replace Baidu statistics with Google statistics
- Update default UA
### Fix
- the project url is not correctly retrieved in some cases
### Other
- Remove invalid Alibaba image-hosting

[History Changelog](doc/log.en.md)

## 👨‍💻 About the author
Commonly used ID [mokeyjay](https://www.mokeyjay.com), IT and ACG lover