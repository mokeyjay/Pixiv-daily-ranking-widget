<h1 align="center">🖼️ Pixiv Daily Ranking Widget</h1>
<p align="center">
    <a href="README.md">中文</a>
    <br><br>
    Want to add a <span style="font-weight: bold">Pixiv Daily Ranking Widget</span> to your website? Now, it only takes one line of code to do it!
    <a href="https://cloud.mokeyjay.com/pixiv/demo.html" target="_blank">DEMO</a>
</p>

## ✨ Features
- Easy to use with one line of `HTML` code
- Adaptive width and height. Recommended minimum size `240px * 380px` (width \* height) or more
- Clicking on the image will redirect to detailed page
- Automatic daily update
- Builtin multi-image-hosting service provider support, on-demand image loading, extremely low resource consumption
- API service provided including ranking update date, thumbnail URL and detail page URL, etc.

## 🤔 How to use
Just add this line to your page
```html
<iframe src="https://cloud.mokeyjay.com/pixiv" style="width:240px; height:380px; border: 0"></iframe>
```

Take `WordPress` as an example. On `wp-admin`, click on **Appearance** -> **Widgets**  
Then add a **Text** or **Custom HTML** widget to the right in the appropriate place and fill in the code above

[Advance Usage](doc/advance-usage.en.md)

## 🛠️ How to deploy
Want to customize the code yourself? Think the service I provided is too slow?  

You can also easily deploy your own widget!
> Requires PHP version >= 5.6

[Deployment Documentation](doc/deploy.en.md)

## 🔌 API
[Ranking data (already uploaded to the image-hosting)](https://cloud.mokeyjay.com/pixiv/?r=api/pixiv-json) (recommended)  
[Ranking data (pixiv url)](https://cloud.mokeyjay.com/pixiv/?r=api/source-json)

`data` is the ranking data; `date` is the ranking date (maybe yesterday or the day before yesterday, depending on the pixiv update time)  

Both API automatically return the corresponding cross-domain header based on the `Origin` or `Referer` in the request header. They are front-end ready.

> The `image` and `url` keys are for compatibility with users of 4.x and earlier versions, you can ignore them

## 🆙 Upgrade Guide
1. [Download ZIP](https://github.com/mokeyjay/pixiv-daily-ranking-widget/releases/latest)
2. Unzip and overwrite the `app` and `index.php` on your server

### From 4.x to 5.0
1. Check the `image_hosting` item's comment from [config.php](config.php#L90), select the most appropriate image hosting option and fill it in your `config.php`
2. Delete all files in 'storage/app' to let the program refresh the ranking data

## 🌟 Changelog
### New
- API service support cross-domain 
- scheduled job to clear historical logs
- support 8 free image-hosting service provider
### Optimization
- Picture display effect
- Left and right arrows will autohide now
- Show works title and author name on mouse hover
- Upgrade front-end dependency package to the latest, reduce dependency
- Improve logging function
- Enrich interface data to fit more scenarios
- Replace Baidu statistics with Google statistics
- Update default UA
### Fix
- Project URL cannot be retrieved correctly in some cases
- A blank image will be downloaded in some cases
### Other
- Remove invalid Alibaba image-hosting

[History](doc/log.en.md)

## 👨‍💻 About author
[mokeyjay](https://www.mokeyjay.com), IT and ACG lover
