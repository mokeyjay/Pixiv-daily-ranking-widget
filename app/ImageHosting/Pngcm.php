<?php

namespace app\ImageHosting;

use app\Libs\Curl;
use app\Libs\Log;
use app\Libs\Str;

/**
 * Pngcm
 * Class Pngcm
 * @package app\ImageHosting
 * @url https://png.cm/
 */
class Pngcm extends ImageHosting
{
    public function upload($path)
    {
        $data = [
            'file' => Curl::getCurlFile($path),
            'name' => pathinfo($path, PATHINFO_BASENAME),
            'uuid' => 'o_1g840' . Str::random(21),
        ];
        $result = Curl::post('https://png.cm/application/upload.php', $data);

        Log::write('[Pngcm 图床]上传：' . json_encode($data));
        Log::write('[Pngcm 图床]返回：' . $result);

        $json = json_decode($result, true);
        if (isset($json['code']) && $json['code'] == 200 && !empty($json['url'])) {
            return $json['url'];
        }

        Log::write('[Pngcm 图床]上传失败', 'ERROR');
        return false;
    }
}