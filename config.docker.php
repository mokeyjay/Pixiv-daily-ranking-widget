<?php
/**
 * 此文件为 docker 环境的配置文件
 * 每个参数的含义见 config.php
 *
 * This file is the docker environment configuration file
 * See config.php for the meaning of each parameter
 */

use app\Libs\Env;

return [
    'url' => Env::getStr('URL'),

    'background_color' => Env::getStr('BACKGROUND_COLOR', 'transparent'),

    'limit' => Env::getStr('LIMIT', 50),

    'service' => Env::getBool('SERVICE', true),

    'log_level' => Env::getArray('LOG_LEVEL'),

    'proxy' => Env::getStr('PROXY'),

    'clear_overdue' => Env::getBool('CLEAR_OVERDUE', true),

    'compress' => Env::getBool('COMPRESS', true),

    'image_hosting' => Env::getArray('IMAGE_HOSTING', ['local']),

    'image_hosting_extend' => [
        'tietuku' => [
            'token' => Env::getStr('IMAGE_HOSTING_EXTEND-TIETUKU-TOKEN'),
        ],
        'smms' => [
            'token' => Env::getStr('IMAGE_HOSTING_EXTEND-SMMS-TOKEN'),
        ],
        'riyugo' => [
            'url' => Env::getStr('IMAGE_HOSTING_EXTEND-RIYUGO-URL'),
            'upload_path' => Env::getStr('IMAGE_HOSTING_EXTEND-RIYUGO-UPLOAD-PATH'),
            'unique_id' => Env::getStr('IMAGE_HOSTING_EXTEND-RIYUGO-UNIQUE-ID'),
            'token' => Env::getStr('IMAGE_HOSTING_EXTEND-RIYUGO-TOKEN'),
        ],
    ],

    'disable_web_job' => Env::getBool('DISABLE_WEB_JOB', false),

    'static_cdn' => Env::getStr('STATIC_CDN', 'bytedance'),

    'header_script' => Env::getStr('HEADER_SCRIPT', ''),
];