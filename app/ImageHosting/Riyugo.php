<?php

namespace app\ImageHosting;

use app\Libs\Curl;
use app\Libs\Log;
use app\Libs\Str;

/**
 * 薄荷图床（国内，香港腾讯云）
 * Class riyugo
 * @package app\ImageHosting
 * @url https://riyugo.com/
 */
class Riyugo extends ImageHosting
{
    public function upload($path)
    {
        $data = [
            'name' => pathinfo($path, PATHINFO_BASENAME),
            'uuid' => 'o_1gbng' . Str::random(22),
            'nameMode' => 'isRenameMode',
            'file' => Curl::getCurlFile($path),
        ];
        $result = Curl::post('https://4ae.cn/localup.php', $data, [
            CURLOPT_HTTPHEADER => [
                'accept: */*',
                'referer: https://riyugo.com/',
                'origin: https://riyugo.com',
            ],
        ]);

        Log::write('[薄荷图床]上传：' . json_encode($data));
        Log::write('[薄荷图床]返回：' . $result);

        $json = json_decode($result, true);
        if (isset($json['result']) && $json['result'] == 'success' && !empty($json['url'])) {
            return $json['url'];
        }

        Log::write('[薄荷图床]上传失败', 'ERROR');
        return false;
    }
}