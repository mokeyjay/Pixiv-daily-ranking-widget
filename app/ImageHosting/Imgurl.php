<?php

namespace app\ImageHosting;

use app\Libs\Curl;
use app\Libs\Log;

/**
 * Imgurl（AWS）
 * Class Imgurl
 * @package app\ImageHosting
 * @url https://www.imgurl.org/
 */
class Imgurl extends ImageHosting
{
    public function upload($path)
    {
        $data = [
            'file' => Curl::getCurlFile($path),
        ];
        $result = Curl::post('https://www.imgurl.org/upload/aws_s3', $data);

        Log::write('[Imgurl 图床]上传：' . json_encode($data));
        Log::write('[Imgurl 图床]返回：' . $result);

        $json = json_decode($result, true);
        if (!empty($json['url'])) {
            return $json['url'];
        }

        Log::write('[Imgurl 图床]上传失败', 'ERROR');
        return false;
    }
}