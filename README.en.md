<h1 align="center">🖼️ Pixiv Daily Ranking Widget</h1>
<p align="center">
    <a href="README.md">中文</a>
    <br><br>
    Want to add a <span style="font-weight: bold">Pixiv Daily Ranking Widget</span> to your website? It's a matter of one line of code!
    <a href="https://cloud.mokeyjay.com/pixiv/demo.html" target="_blank">DEMO</a>
</p>

## ✨ Features
- Easy to use with one line of `HTML` code
- Adaptive width and height. A minimum size of `240px * 380px` (width \* height) or more is recommanded
- Redirect to artwork page by clicking on the widget
- Automatic daily update
- Low system resource utilisation with supports for multiple image hosting platforms and on-demand image loading
- Offering an API that includes ranking update date, thumbnail URL and detail page URL etc.

## 🤔 How to use
Just add the below code to your page
```html
<iframe src="https://cloud.mokeyjay.com/pixiv" style="width:240px; height:380px; border: 0"></iframe>
```

Taking `WordPress` as an example. On `wp-admin`, click on **Appearance** -> **Widgets**  
Then add a **Text** or **Custom HTML** widget as deemed appropriate on the right and fill the code above in

[Advance Usage](doc/advance-usage.en.md)

## 🛠️ How to deploy
Wanted to customize the code yourself? Thought the service I provided is slow in speed?  

You can also easily deploy your own widget!
> Requires PHP version >= 5.6

[Deployment Documentation](doc/deploy.en.md)

## 🔌 APIs
[Ranking data (images hosted privately)](https://cloud.mokeyjay.com/pixiv/?r=api/pixiv-json) (recommended)  
[Ranking data (pixiv url)](https://cloud.mokeyjay.com/pixiv/?r=api/source-json)

In which `data` is the data of the ranking table; `date` is the date of ranking (could be yesterday or the day before, as the time of refresh on Pixiv is not certain)  

Both APIs automatically return the respective cross-domain header according to `Origin` or `Referer` within the request header. The APIs are front-end ready.

> The `image` and `url` keys are for compatibility purposes for users of 4.x or earlier versions, they can be ignored

## 🆙 Upgrading Guide
1. [Download the Source Code](https://github.com/mokeyjay/pixiv-daily-ranking-widget/releases/latest)
2. Unzip and overwrite the `app` and `index.php` to on your server

### Upgrading From 4.x to 5.x
1. Check the code comment of `image_hosting` item in [config.php](config.php#L90), select the most suitable image hosting option and fill it in your `config.php`
2. Delete all files in `storage/app` to enable the application refreshing the ranking data

## 🌟 Changelog
### New Features
- JD, Riyugo image hosting
### Other
- Removed the invalid Baidu, Imgstop image hosting option
- Replace Google Analytics with Baidu Analytics

[History](doc/log.en.md)

## 👨‍💻 About author
[mokeyjay](https://www.mokeyjay.com), IT and ACG lover
