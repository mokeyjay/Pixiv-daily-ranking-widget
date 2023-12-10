# 高级用法
```html
<iframe src="https://pixiv.mokeyjay.com" style="width:240px; height:380px; border: 0"></iframe>
```

## 自定义宽度或高度
此挂件支持自适应宽高，你只需要修改代码中的 `width` 或者 `height` 的值即可  
例如你想要宽 300、高 500，则需要将上述代码中的  
`width:240px; height:380px;` 修改为 `width:300px; height:500px;`  

> 因为大多数 pixiv 画作的预览图都是 240*380 左右的分辨率，因此一般并不需要修改宽高度

## 自定义背景色
默认背景色为透明，如需修改背景色，可以添加 url 参数 `color`  
例如你想要红色背景，则将上述代码中的  
`/pixiv` 修改为 `/pixiv?color=f00`  

其中 `f00` 即表示红色，支持 3 或 6 位 [十六进制颜色值](https://baike.baidu.com/item/%E5%8D%81%E5%85%AD%E8%BF%9B%E5%88%B6%E9%A2%9C%E8%89%B2%E7%A0%81/10894232) ，无需 # 号

## 自定义数量
默认只显示排行前 50 的作品。你可以通过 url 参数 `limit` 来修改  
> limit 最大值为 500，因为排行榜最多就只有 500 名

例如你只想要前 10，则将上述代码中的  
`/pixiv` 修改为 `/pixiv?limit=10`

> ⚠️ 该值无法超过 `config.php` 中的 limit 配置

> 🌟 如需同时使用多个 url 参数，你可以用 `&` 来连接它们。例如 `?color=f00&limit=10`