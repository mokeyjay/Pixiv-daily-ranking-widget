<?php

namespace app\ImageHosting;

use app\Libs\Curl;
use app\Libs\Log;

/**
 * Tsesze（268608 的英文首字母缩写）
 * Class Tsesze
 * @package app\ImageHosting
 * @url https://www.268608.com/
 */
class Tsesze extends ImageHosting
{
    public function upload($path)
    {
        $data = [
            'file' => Curl::getCurlFile($path),
        ];
        $result = Curl::post('https://www.268608.com/upload/localhost', $data);

        Log::write('[Tsesze 图床]上传：' . json_encode($data));
        Log::write('[Tsesze 图床]返回：' . $result);

        $json = json_decode($result, true);
        if (!empty($json['url'])) {
            return $json['url'];
        }

        Log::write('[Tsesze 图床]上传失败', 'ERROR');
        return false;
    }
}