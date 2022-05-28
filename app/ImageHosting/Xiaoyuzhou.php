<?php

namespace app\ImageHosting;

use app\Libs\Curl;
use app\Libs\Log;

/**
 * 小宇宙图床
 * 鸣谢：[@metowolf](https://github.com/metowolf)
 * Class 小宇宙图床
 * @package app\ImageHosting
 */
class Xiaoyuzhou extends ImageHosting
{
    protected function getToken()
    {
        $result = Curl::get('https://balloon.midway.run/qiniu/provision?midway-project-id=v6worU4NnWyL&type=IMAGE', [
            CURLOPT_HTTPHEADER => [
                'User-Agent: Xiaoyuzhou/2.27.1 (build:839; iOS 15.4.1)'
            ],
        ]);

        Log::write('[小宇宙图床]获取 Token：' . $result);

        $json = json_decode($result, true);

        return $json['token'] ?: false;
    }

    public function upload($path)
    {
        $data = [
            'file' => Curl::getCurlFile($path),
            'token' => $this->getToken(),
        ];
        $result = Curl::post('https://upload.qiniup.com/', $data, [
            CURLOPT_HTTPHEADER => [
                'User-Agent: Podcast/2.27.1 (app.podcast.cosmos; build:839; iOS 15.4.1) Alamofire/5.4.3'
            ],
        ]);

        Log::write('[小宇宙图床]上传：' . json_encode($data));
        Log::write('[小宇宙图床]返回：' . $result);

        $json = json_decode($result, true);
        if(!empty($json['file']['key'])){
            return 'https://image.xyzcdn.net/' . $json['file']['key'];
        }

        Log::write('[小宇宙图床]上传失败', 'ERROR');
        return false;
    }
}