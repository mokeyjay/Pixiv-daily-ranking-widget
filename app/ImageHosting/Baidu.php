<?php

namespace app\ImageHosting;

use app\Libs\Curl;
use app\Libs\Tools;

/**
 * 百度图床
 * 鸣谢：[@metowolf](https://github.com/metowolf)
 * Class Baidu
 * @package app\ImageHosting
 * @url https://baijiahao.baidu.com/
 */
class Baidu extends ImageHosting
{
    public function upload($path)
    {
        $data = [
            'media' => Curl::getCurlFile($path),
            'no_compress' => '1',
            'id' => 'WU_FILE_0',
            'is_avatar' => '0',
            'type' => 'image',
            'name' => pathinfo($path, PATHINFO_FILENAME) . '.jpg'
        ];
        $result = Curl::post('https://baijiahao.baidu.com/builderinner/api/content/file/upload', $data, [
            CURLOPT_HTTPHEADER => [
                'Origin: https://baijiahao.baidu.com',
                'Referer: https://baijiahao.baidu.com/builder/app/register?type=individual'
            ],
        ]);

        Tools::log('[百度图床]上传：' . json_encode($data));
        Tools::log('[百度图床]返回：' . $result);

        $json = json_decode($result, true);
        if(isset($json['errno']) && $json['errno'] == 0 && isset($json['ret']['org_url'])){
            return $json['ret']['org_url'];
        }

        Tools::log('[百度图床]上传失败', 'ERROR');
        return false;
    }
}