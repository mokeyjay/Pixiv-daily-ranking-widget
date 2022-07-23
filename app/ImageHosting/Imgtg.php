<?php

namespace app\ImageHosting;

use app\Libs\Curl;
use app\Libs\Log;

/**
 * Imgtg（国内 CDN 但是鉴黄超级严格）
 * Class Imgtg
 * @package app\ImageHosting
 * @url https://Imgtg.com/
 */
class Imgtg extends ImageHosting
{
    public function upload($path)
    {
        $result = Curl::get('https://imgtg.com', [] , true);
        $authToken = preg_match('|auth_token = "(.*?)";|', $result['html'], $matches) ? $matches[1] : false;

        if (!$authToken) {
            Log::write('[Imgtu 图床]获取 token 失败', 'ERROR');
            return false;
        }

        $data = [
            'source' => Curl::getCurlFile($path),
            'type' => 'file',
            'action' => 'upload',
            'timestamp' => substr(microtime(true) * 1000, 0, 13),
            'auth_token' => $authToken,
            'nsfw' => '0',
        ];
        $result = Curl::post('https://imgtg.com/json', $data, [
            CURLOPT_HTTPHEADER => [
                'accept: application/json',
                'referer: https://imgtg.com',
                'origin: https://imgtg.com',
            ],
            CURLOPT_COOKIE => $result['cookie'],
        ]);

        Log::write('[Imgtg 图床]上传：' . json_encode($data));
        Log::write('[Imgtg 图床]返回：' . $result);

        $json = json_decode($result, true);
        if(!empty($json['image']['image']['url'])){
            return $json['image']['image']['url'];
        }

        Log::write('[Imgtg 图床]上传失败', 'ERROR');
        return false;
    }
}