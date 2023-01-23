<?php
/**
 * 此文件为 docker 环境的配置文件
 * 参数的说明相间 config.php(这里没写是不想维护两份)
 */

use app\Libs\Env;

return [
    'url' => Env::getStrEnv('URL'),

    'background_color' => Env::getStrEnv('BACKGROUND_COLOR', 'transparent'),

    'limit' => Env::getStrEnv('LIMIT', 50),

    'service' => Env::getBoolEnv('SERVICE', true),

    'log_level' => Env::getArrayEnv('LOG_LEVEL'),

    'proxy' => Env::getStrEnv('PROXY'),

    'clear_overdue' => Env::getBoolEnv('CLEAR_OVERDUE', true),

    'compress' => Env::getBoolEnv('COMPRESS', true),

    'image_hosting' => Env::getArrayEnv(
        'IMAGE_HOSTING',
        ['tsesze', 'fifty-eight', 'chkaja', 'pngcm', 'catbox', 'local']
    ),

    'image_hosting_extend' => [
        'tietuku' => [
            'token' => Env::getStrEnv('IMAGE_HOSTING_EXTEND-TIETUKU-TOKEN'),
        ],
        'smms' => [
            'token' => Env::getStrEnv('IMAGE_HOSTING_EXTEND-SMMS-TOKEN'),
        ],
        'riyugo' => [
            'url' => Env::getStrEnv('IMAGE_HOSTING_EXTEND-RIYUGO-URL'),
            'upload_path' => Env::getStrEnv('IMAGE_HOSTING_EXTEND-RIYUGO-UPLOAD-PATH'),
            'unique_id' => Env::getStrEnv('IMAGE_HOSTING_EXTEND-RIYUGO-UNIQUE-ID'),
            'token' => Env::getStrEnv('IMAGE_HOSTING_EXTEND-RIYUGO-TOKEN'),
        ],
    ],

    'disable_web_job' => Env::getBoolEnv('DISABLE_WEB_JOB', false),

    'static_cdn' => Env::getStrEnv('STATIC_CDN', 'bytedance'),
];