<?php

namespace app\ImageHosting;

use app\Libs\Curl;
use app\Libs\Tools;

/**
 * 阿里巴巴图床
 * 鸣谢：[@metowolf](https://github.com/metowolf)
 * Class Alibaba
 * @package app\ImageHosting
 * @url https://www.alibaba.com/
 */
class Alibaba extends ImageHosting
{
    public function upload($path)
    {
        $data = [
            'file' => Curl::getCurlFile($path),
            'scene' => 'aeMessageCenterImageRule',
            'name' => 'image.jpg'
        ];
        $result = Curl::post('https://kfupload.alibaba.com/mupload', $data, [
            CURLOPT_USERAGENT => 'iAliexpress/8.25.0 (iPhone; iOS 14.5; Scale/3.00)',
        ]);

        Tools::log('[阿里巴巴图床]上传：' . json_encode($data));
        Tools::log('[阿里巴巴图床]返回：' . $result);

        $json = json_decode($result, true);
        if(isset($json['code']) && $json['code'] == 0 && isset($json['url'])){
            return $json['url'];
        }

        Tools::log('[阿里巴巴图床]上传失败', 'ERROR');
        return false;
    }
}