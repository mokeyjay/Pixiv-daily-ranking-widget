<?php

namespace app\ImageHosting;

use app\Libs\Curl;
use app\Libs\Log;
use app\Libs\Str;

/**
 * 骚人图床（国内，有鉴黄，偶尔会有图 404）
 * Class Saoren
 * @package app\ImageHosting
 * @url https://tu.sao.ren/
 */
class Saoren extends ImageHosting
{
    public function upload($path)
    {
        $data = [
            'file' => Curl::getCurlFile($path),
            'name' => pathinfo($path, PATHINFO_BASENAME),
            'uuid' => 'o_1g840' . Str::random(21),
        ];
        $result = Curl::post('https://tu.sao.ren/file.php', $data);

        Log::write('[骚人图床]上传：' . json_encode($data));
        Log::write('[骚人图床]返回：' . $result);

        $json = json_decode($result, true);
        if (isset($json['code']) && $json['code'] == 200 && !empty($json['url'])) {
            return $json['url'];
        }

        Log::write('[骚人图床]上传失败', 'ERROR');
        return false;
    }
}