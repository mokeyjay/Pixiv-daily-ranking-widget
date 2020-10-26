<?php

return [
    /**
     * 本项目的url地址，必须以 / 结尾
     * 留空则自动获取，一般情况下留空即可
     *
     * P.S. 如果你准备通过 cli 方式来触发 refresh 任务，且使用了 local 图床，则此项必填
     *      否则生成的图片完整 url 可能出现问题
     */
    'url' => '',

    /**
     * 背景颜色。默认值为 transparent （透明）。你也可以通过get方式传参 color 来设置
     */
    'background_color' => 'transparent',

    /**
     * 显示和缓存的图片最大数量（范围1-500）
     * 例如将此值设为 10 则可以做出 Top10 的效果
     * 也可防止部分辣鸡主机在缓存图片时占用过多资源导致卡死或报警
     * 一般情况下默认的 50 就行
     */
    'limit' => 50,

    /**
     * 是否对外提供服务
     * 为 true 时，任何人都可通过 url 的 get 参数来临时修改 background_color 和 limit 的值
     */
    'service' => true,

    /**
     * 日志级别。可多选：DEBUG、ERROR 或留空不记录任何日志
     */
    'log_level' => [],

    /**
     * 代理服务器配置。例如 127.0.0.1:1080
     * 留空为不使用代理
     */
    'proxy' => '',

    /**
     * 自动删除缓存在本地的过期的（即今天之前的）缩略图
     */
    'clear_overdue' => true,

    /**
     * 压缩缩略图，在几乎不损失画质的前提下减小50%左右的体积，降低服务器带宽压力
     * 需要启用PHP的 GD 扩展
     */
    'compress' => true,

    /**
     * 图床名称
     * 可多选：baidu、toutiao、netease、suning、smms、local、tietuku
     * baidu=百度、toutiao=今日头条、netease=网易、suning=苏宁、smms=Sm.ms图床、local=服务器本地、tietuku=贴图库
     * （推荐度按照顺序从高到低）
     *
     * 推荐填写多个图床，如果其中一个图床上传失败，则将按照顺序继续尝试其他图床
     */
    'image_hosting' => ['baidu', 'toutiao', 'netease', 'suning', 'smms', 'local'],

    /**
     * 图床扩展配置信息
     */
    'image_hosting_extend' => [
        'tietuku' => [
            'token' => ''
        ],
        'smms' => [
            'token' => '',
        ],
    ]
];