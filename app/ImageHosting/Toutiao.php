<?php

namespace app\ImageHosting;

use app\Libs\Curl;
use app\Libs\Tools;

/**
 * 今日头条图床
 * 鸣谢：[@metowolf](https://github.com/metowolf)
 * Class Toutiao
 * @package app\ImageHosting
 * @url https://mp.toutiao.com/
 */
class Toutiao extends ImageHosting
{
    public function upload($path)
    {
        $data = [
            'photo' => Curl::getCurlFile($path),
        ];
        $result = Curl::post('https://mp.toutiao.com/upload_photo/?type=json', $data, [
            CURLOPT_HTTPHEADER => [
                'Origin: https://mp.toutiao.com',
                'Referer: https://mp.toutiao.com/profile_register_v3/register/register-normal/2'
            ],
        ]);

        Tools::log('[今日头条图床]上传：' . json_encode($data));
        Tools::log('[今日头条图床]返回：' . $result);

        $json = json_decode($result, true);
        if(isset($json['message']) && $json['message'] == 'success' && isset($json['web_url'])){
            return $json['web_url'];
        }

        Tools::log('[今日头条图床]上传失败', 'ERROR');
        return false;
    }
}