<?php
/**
 * 此文件为 docker 环境的配置文件
 * 每个参数的含义见 config.php
 *
 * This file is the docker environment configuration file
 * See config.php for the meaning of each parameter
 */

use app\Libs\Env;

Env::init();

return [
    'url' => Env::getStr('URL'),

    'background_color' => Env::getStr('BACKGROUND_COLOR', 'transparent'),

    'limit' => Env::getStr('LIMIT', 50),

    'service' => Env::getBool('SERVICE', true),

    'log_level' => Env::getArray('LOG_LEVEL', ['DEBUG', 'ERROR']),

    'proxy' => Env::getStr('PROXY'),

    'clear_overdue' => Env::getBool('CLEAR_OVERDUE', true),

    'compress' => Env::getBool('COMPRESS', true),

    'image_hosting' => Env::getArray('IMAGE_HOSTING', ['local']),

    'image_hosting_extend' => [
        'tietuku' => [
            'token' => Env::getStr('IMAGE_HOSTING_EXTEND_TIETUKU_TOKEN'),
        ],
        'smms' => [
            'token' => Env::getStr('IMAGE_HOSTING_EXTEND_SMMS_TOKEN'),
        ],
        'riyugo' => [
            'url' => Env::getStr('IMAGE_HOSTING_EXTEND_RIYUGO_URL'),
            'upload_path' => Env::getStr('IMAGE_HOSTING_EXTEND_RIYUGO_UPLOAD_PATH'),
            'unique_id' => Env::getStr('IMAGE_HOSTING_EXTEND_RIYUGO_UNIQUE_ID'),
            'token' => Env::getStr('IMAGE_HOSTING_EXTEND_RIYUGO_TOKEN'),
        ],
    ],

    'disable_web_job' => Env::getBool('DISABLE_WEB_JOB', true),

    'header_script' => Env::getStr('HEADER_SCRIPT', ''),

    'ranking_type' => Env::getStr('RANKING_TYPE', ''),
];