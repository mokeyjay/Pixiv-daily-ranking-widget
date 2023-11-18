# History Update Log
## 5.2
### New Features
- Support Docker (thanks to @hujingnb)
- `header_script` configuration item, so you can customize statistics code or js script
### Optimizations
- Used the proxy service provided by pixiv.cat as a final guarantee plan
- Riyugo image hosting changed to vip version, you need to buy a vip account before use it (free version is no longer available)
- Improve image download integrity check mechanism
- Removed built-in statistics code
### Other
- Removed invalid JD, imgurl, imgtg and saoren image hosting

## 5.1
### New Features
- JD, Riyugo, FiftyEight image hosting
- `static_cdn` configuration, you can choose the front-end static resource CDN provider
### Optimizations
- Rewritten left and right arrows, fixed some issues with it
### Other
- Removed the invalid Baidu, Imgstop image hosting option
- Replace Google Analytics with Baidu Analytics

## 5.0
### New Features
- Supporting cross-domain on the APIs
- Scheduled job to clear historical logs
- Supporting 8 free image hosting
### Optimizations
- Picture display effect
- Left and right arrows will now be hidden automatically
- Display arwork title and author on mouse hover
- Upgrade front-end dependency packages to their latest while reducing amount of dependencies
- Improved logging function
- Enrich interface data to fit more use cases
- Replace Baidu Analytics with Google Analytics
- Update default User Agent
### Fixes
- Project URL cannot be retrieved correctly under certain scenarios
- A blank image will be downloaded under certain scenarios
### Other
- Removed the invalid Alibaba image hosting option

## 4.4.2
- Update default UA of Curl class
- Remove invalid image host
- Update Alibaba image host
- Add `disable_web_job` switch, see `config.php` for details

## 4.4.1
- Update default UA of Curl class
- Fix an error of getting wrong project URL in some cases
- Fix the retry loop when fails to get ranking data.
- Optimize the logic to determine whether the data needs to be updated
- Delete the obsolete Alibaba image host

## 4.4
- Use official ajax interface to get ranking data
- Add Alibaba, Baidu, toutiao.com image host API
- Update smms image host API to v2 version
- Remove deprecated img.sb image host API
- Remove unaccessible Jingdong image host API
- Remove statistics code on loading page
- Use comprehensive ranking data instead of just illustrations rank
- Expanded maximum number of returned images to 500, corresponding to the official limit
- The value of  `service` configuration item no longer affects the limit.
- Other optimizations and bug fixes

## 4.3
- Fix failed to update the Pixiv ranking page due to pixiv change.
- Use https when jumping to detail page

## 4.2
- Fix failed to update the Pixiv ranking page due to pixiv change.
- Add a maximum number of retries if failing to get the ranking data.

## 4.1
- Support transparent background

## 4.0
- New version with almost all the codes rewritten, more new features and bugs for you to discover!
- Since pixiv has enabled global anti-hotlink, the `download` and `url_cache` options have been removed. Now it will force download thumbnails, and then upload them to the corresponding image host or store them locally on the server according to the configuration

> Mumbling: I initially stated this project just for fun, and never expected the code to get so ugly as features piled up. It's so shameful that this project became my most starred project on GitHub. Luckily I spent some days to deal with the 4.0 version, and it feels better now.
>
> BTW this little thing now supports multiple image host, it will save hundreds of gigs of traffic every month now yingyingying

## 3.0
- Add `$download_proxy` configuration option
- Since Pixiv's has enabled anti-hotlink and images cannot be displayed directly, `$download` is enabled by default.
  For better display, self-deployed users are recommended to configure a timed task to trigger `download.php` at 0:00 every day

## 2.9
- Fix the problem of not functioning normally due to Pixiv changes

## 2.8
- Try to optimize update lock to prevent repeated updates under high concurrency
- Separate the configuration item `Conf::$url_cache` from `Conf::$download`, now you can only cache the image url instead of the thumbnail url
- Add support for tietuku.cn image host

> The free version of tietuku.cn is not very good and does not support https, I suggest using sm.ms first and tietuku.cn only as a fallback option.
> Due to some problems with the previous update lock not working well under high concurrency, my server IP was blocked by sm.ms image host due to duplicate uploads. And I personally can't afford to support the high CDN cost. <del>So effective immediately **Plan One** no longer provides official CDN acceleration and instead gets images directly from the Pixiv site</del  
> Plan 1 is currently supported by 360 Site Defender CDN

## 2.7
- Add image compression feature to reduce server bandwidth stress (requires GD library)
- Fix sm.ms image host support to reduce chances to fail
- Add sm.ms image host upload log

> If there is a problem with opening `$enable_smms`, please paste the log file when giving feedback

## 2.6
- Add sm.ms image host support. With one click to enable, it can significantly reduce the server bandwidth stress and save traffic. Thanks to [@Showfom](https://sb.sb/) for providing the image host

> I'm not going to tell you that I added this feature because it hurts that the several gigabytes of traffic will be consumed every day on Plan 1
> If the upload fails 3 times repeatedly, image will be read locally from the server to ensure normal access

## 2.5
- Fix the problem of not functioning normally due to Pixiv changes
- Pixiv supports native https now! Hooray!

## 2.4
- Fix the problem that the `limit` parameter of URL is invalid under certain circumstances
- Repair the caching problem of **Plan 1**
- Fix the SSL certificate problem

## 2.3
- Replace the reference url of front-end library, fix the problem of slow loading with China Mobile as ISP
- Add adaptive protocol feature, fix the problem of the small green lock being affected when the cache is disabled or the cache peogress is not finished
- The above update comes from the friendly PR of @LingWuLuKong, let's PRPR her together to show our appreciation!
- The Plan One from mokeyJay a service now supports HTTPS. The CDN charge is high, please use and cherish!
- If it is abused to the point that I can not afford the cost, then the service may be suspended~
- If the number of visits is high, it is recommended to build your own services, thank you for your support and understanding!

## 2.2
- Optimize download threads to support self-deployed HTTPS

## 2.1
- I must be drunk when planning the 2.0 roadmap to constrain all logic into one file. Although the overall performance has been improved, the same problems still occur in some cases. For example, the thumbnail will fail to download, PHP will timeout so the download will break, and so on. So when I tested it and realized this, I started working on a new version <del>Facepalm</del>.
- Remove the auto update lock mechanism, the thumbnails will no longer be re-downloaded when they already exist and are valid. Prevents thumbnail download failures due to network fluctuations or timeouts

## 2.0
- Overall refactoring, overall performance is greatly optimized
- Add an automatic update lock mechanism to avoid wasting resources on concurrent updates under high concurrency scenario
- New pseudo multi-thread automatic update mechanism, background update does not affect widget use
- Update failure retrial, to avoid the failure of retrieving some images due to network problems

## Motivations
I was talking to a friend the other day, and he said he wanted to show the daily ranking of [Pixiv](http://www.pixiv.net/) in the sidebar of his blog. I am also an `ACG` lover myself, so I wanted to write this as well. I finally had time last night and spent more than half an hour writing it. It looks great on [my own blog](https://www.mokeyjay.com), so I polished it and added some features and made it open source in case you have the same requirements.