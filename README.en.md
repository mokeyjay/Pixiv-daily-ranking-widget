<h1 align="center">🖼️ Pixiv Daily Ranking Widget</h1>
<p align="center">
    <a href="https://github.com/mokeyjay/Pixiv-daily-ranking-widget/blob/master/README.md">中文</a>
    <br><br>
    Want to add a <span style="font-weight: bold">Pixiv Daily Ranking Widget</span> to your website? It's a matter of one line of code!
    <a href="https://pixiv.mokeyjay.com/demo.html" target="_blank">DEMO</a>
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
<iframe src="https://pixiv.mokeyjay.com" style="width:240px; height:380px; border: 0"></iframe>
```

Taking `WordPress` as an example. On `wp-admin`, click on **Appearance** -> **Widgets**  
Then add a **Text** or **Custom HTML** widget as deemed appropriate on the right and fill the code above in

[Advance Usage](https://github.com/mokeyjay/Pixiv-daily-ranking-widget/blob/master/doc/advance-usage.en.md)

## 🛠️ How to deploy
Wanted to customize the code yourself? Thought the service I provided is slow in speed?  

You can also easily deploy your own widget!
> Requires Docker or PHP version >= 5.6

[Deployment Documentation](https://github.com/mokeyjay/Pixiv-daily-ranking-widget/blob/master/doc/deploy.en.md)

## 🔌 APIs
[Ranking data (images hosted privately)](https://pixiv.mokeyjay.com/?r=api/pixiv-json) (recommended)  
[Ranking data (pixiv url)](https://pixiv.mokeyjay.com/?r=api/source-json)

In which `data` is the data of the ranking table; `date` is the date of ranking (could be yesterday or the day before, as the time of refresh on Pixiv is not certain)  

Both APIs automatically return the respective cross-domain header according to `Origin` or `Referer` within the request header. The APIs are front-end ready.

> The `image` and `url` keys are for compatibility purposes for users of 4.x or earlier versions, they can be ignored

## 🆙 Upgrading Guide
### Upgrading From 5.2 to 6.0
1. [Download the Source Code](https://github.com/mokeyjay/pixiv-daily-ranking-widget/releases/latest)
2. Unzip and overwrite the `app` and `index.php` to your server
> **⚠️ For Docker User**
> - Please replace all `-` in the environment variable name with `_`
> - Docker Image migration from Docker Hub to [ghcr.io](https://github.com/mokeyjay/Pixiv-daily-ranking-widget/pkgs/container/pixiv-daily-ranking-widget)

## 🌟 Changelog
### New Features
- Added `ranking_type` configuration option, which now allows you to select whether to fetch the overall or illustration/manga daily rankings.  
- Added image preloading to improve the experience in poor network environments.
### Optimizations
- Completely rewritten the frontend with more elegant animation effects.  
- Removed the dependency on Bootstrap for faster loading.  
- Switched to using the official PHP and Nginx packages.
- No longer repeatedly check integrity when retrieving images from local storage.
- The `background_color` configuration now supports [more colors](https://developer.mozilla.org/en-US/docs/Web/CSS/background-color)
### Fixes
- Some environment variables cannot be obtained normally in some cases
- The scheduled task actually runs once every hour, not every half hour as stated in the documentation
### Other
- Removed the `static_cdn` configuration option due to the removal of the dependency on Bootstrap.
- Removed the invalid `Pngcm` and `Tsesze` image-hosting
- Docker image migration to [ghcr.io](https://github.com/mokeyjay/Pixiv-daily-ranking-widget/pkgs/container/pixiv-daily-ranking-widget)
- Docker image timezone defaults to Shanghai

[History](https://github.com/mokeyjay/Pixiv-daily-ranking-widget/blob/master/doc/log.en.md)

## 👨‍💻 About author
[mokeyjay](https://www.mokeyjay.com), IT and ACG lover
