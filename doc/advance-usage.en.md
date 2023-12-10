# Advanced usage
```html
<iframe src="https://pixiv.mokeyjay.com" style="width:240px;  height:380px;  border: 0"></iframe>
```

## Custom width or height
This widget supports adaptive width and height. You only need to change the value of `width` or `height` in the code
For example, if you want a width of 300px and height of 500px , then change the code above from  
`width:240px;  height:380px;` 

to `width:300px; height:500px;`

> Since most pixiv images are previewed at a resolution of about 240*380, there is usually no need to modify the width and height.

## Custom background color
The default background color is transparent. If you want to change the background color, you can add a parameter `color` to the URL.
For example, if you want a red background, then change the above code from  
`/pixiv` 

to `/pixiv?color=f00`

`f00` means red, supports 3- or 6-digit [HTML Color Codes](https://htmlcolorcodes.com/), **without the # sign**

## Customize the returned pic number
By default, only the top 50 entries are displayed. You can change this with the URL parameter `limit`.
> the limit has a maximum of 500 since the maximum order of Pixiv Ranking is 500.

For example, if you only want the first 10, then change the above code from  
`/pixiv` to `/pixiv?limit=10`

> ⚠️ This value cannot exceed the limit configuration in `config.php`

> 🌟 To use more than one URL argument at a time, you can connect them with `&`. Such as `?color=f00&limit=10`