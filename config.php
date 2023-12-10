<?php

return [
    /**
     * 本项目的url地址，必须以 / 结尾
     * 留空则自动获取，一般情况下留空即可
     *
     * P.S. 如果你准备通过 cli 方式来触发 refresh 任务，且使用了 local 图床，则此项必填
     *      否则生成的图片完整 url 可能出现问题
     *
     * The url address of this project, must end with /
     * Leave it blank to get it automatically, normally leave it blank
     *
     * P.S. If you want to trigger the refresh job via cli, and you are using a local image-hosting, this field is required
     *      Otherwise, the generated image full URL may have problems
     */
    'url' => '',

    /**
     * 背景颜色。默认值为 transparent （透明）。你也可以通过 url 参数 color 来设置
     * Background color. The default value is transparent. You can also set the background color by url parameter 'color'
     */
    'background_color' => 'transparent',

    /**
     * 显示和缓存的图片最大数量（范围1-500）
     * 例如将此值设为 10 则可以做出 Top10 的效果
     * 也可防止部分辣鸡主机在缓存图片时占用过多资源导致卡死或报警
     * 一般情况下默认的 50 就行
     *
     * Maximum number of images to display and cache (range 1-500)
     * For example, if you set this value to 10, you can make a Top10 effect
     * It also prevents some low-performance hosts from using too many resources when caching images, which can lead to jamming or alarms
     * Usually the default 50 is fine
     */
    'limit' => 50,

    /**
     * 是否对外提供服务
     * 为 true 时，任何人都可通过 url 的 get 参数来临时修改 background_color 和 limit 的值
     *
     * Whether to provide external services
     * When true, anyone can temporarily change the values of background_color and limit by the url parameter
     */
    'service' => true,

    /**
     * 日志级别。可多选：DEBUG、ERROR 或留空不记录任何日志
     * Logging level. Multiple options: DEBUG, ERROR or leave blank to not record any logs
     */
    'log_level' => [],

    /**
     * 代理服务器配置。例如 127.0.0.1:1080
     * 留空为不使用代理
     *
     * Proxy server configuration. For example 127.0.0.1:1080
     * Leave blank to not use proxy
     */
    'proxy' => '',

    /**
     * 每次更新排行榜数据后，自动删除过期的本地缓存缩略图
     * Automatically delete expired local cache thumbnails after each ranking data update
     */
    'clear_overdue' => true,

    /**
     * 压缩缩略图，在几乎不损失画质的前提下减小 50% 左右的体积，降低服务器带宽压力
     * 需要启用 PHP 的 GD 扩展
     *
     * Compress thumbnails to reduce the size by about 50% with almost no loss of image quality, reducing server bandwidth pressure
     * Need the GD extension for PHP
     */
    'compress' => true,

    /**
     * 图床名称
     * （推荐度按照顺序从高到低）
     *
     * 推荐填写多个图床，如果其中一个图床上传失败，则将按照顺序继续尝试其他图床
     *
     * Image-Hosting
     * (Recommendation is ranked from highest to lowest)
     *
     * It is recommended to fill in more than one image-hosting, if one of them fails to upload, it will continue to try other image-hosting in order
     */
    'image_hosting' => ['fifty-eight', 'chkaja', 'catbox', 'local'],

    /**
     * 图床扩展配置信息
     * Extend Configuration information for the image-hosting
     */
    'image_hosting_extend' => [
        'tietuku' => [
            'token' => ''
        ],
        'smms' => [
            'token' => '',
        ],
        // 薄荷图床
        'riyugo' => [
            // 客服提供的会员专属网址。例如 https://r789.com/1234，必须以 / 结尾
            'url' => '',
            // 要上传到的文件夹。通常可以留空
            'upload_path' => '',
            // 管理后台-设置 中的 唯一用户ID
            'unique_id' => '',
            // 登录管理后台后，filemanagerXXXXXXXXX 这个 cookie 的值
            // （XXXXXXXX 是你的唯一用户 ID）
            'token' => '',
        ],
    ],

    /**
     * 禁用 web 访问的方式触发 job 更新，仅限 cli 方式触发
     * 由于部分环境 web 超时时间不够，会导致更新操作不断被触发但又无法完成整个更新流程
     * 因此添加一个开关，避免 web 触发更新，节约服务器资源
     *
     * Disable web-trigger update job, cli-trigger only
     * See doc/deploy.en.md
     */
    'disable_web_job' => false,

    /**
     * 放置在页面 <header> 标签下的 js 脚本内容，通常用来放置统计代码
     * 无需 <script> 标签
     *
     * Js script content placed under the <header> tag of the page, usually used to place statistical code
     * Doesn't need <script> tag
     */
    'header_script' => '',

    /**
     * 排行榜类型。支持 综合、插画、漫画 三种类型
     * 留空为综合； illust 为插画； manga 为漫画
     *
     * The type of ranking.
     * Support three types: synthesis, illustration and manga.
     * Leave '' for synthesis, 'illust' for illustration, 'manga' for manga
     */
    'ranking_type' => '',
];