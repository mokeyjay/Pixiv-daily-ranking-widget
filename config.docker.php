<?php
/**
 * 此文件为 docker 环境的配置文件
 * 参数的说明相间 config.php(这里没写是不想维护两份)
 */

// 检查布尔环境变量是否存在, 若不存在, 返回默认值
function getBoolEnv($name, $default=false){
    $data = getenv($name);
    if($data === false) return $default;
    if(strtolower(strval($data)) === 'false') return false;
    return true;
}

// 检查数组环境变量是否存在, 若不存在, 返回默认值
// 数组的值为 , 分割的字符串
function getArrayEnv($name, $default=[]){
    $data = getenv($name);
    if($data === false) return $default;
    return explode(',', $data);
}

return [
    'url' => getenv('URL') ?: '',

    'background_color' => getenv('BACKGROUND_COLOR') ?: 'transparent',

    'limit' => getenv('LIMIT') ?: 50,

    'service' => getBoolEnv('SERVICE', true),

    'log_level' => getArrayEnv('LOG_LEVEL'),

    'proxy' => getenv('PROXY') ?: '',

    'clear_overdue' => getBoolEnv('CLEAR_OVERDUE', true),

    'compress' => getBoolEnv('COMPRESS', true),

    'image_hosting' => getArrayEnv(
        'IMAGE_HOSTING',
        ['jd', 'riyugo', 'fifty-eight', 'saoren', 'tsesze', 'imgtg', 'chkaja', 'pngcm', 'catbox', 'imgurl', 'local']
    ),

    'image_hosting_extend' => [
        'tietuku' => [
            'token' => getenv('IMAGE_HOSTING_EXTEND-TIETUKU-TOKEN') ?: ''
        ],
        'smms' => [
            'token' => getenv('IMAGE_HOSTING_EXTEND-SMMS-TOKEN') ?: '',
        ],
    ],

    'disable_web_job' => getBoolEnv('DISABLE_WEB_JOB', false),

    'static_cdn' => getenv('STATIC_CDN') ?: 'bytedance',
];