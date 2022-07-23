<?php

namespace app\ImageHosting;

use app\Libs\Curl;
use app\Libs\Log;
use app\Libs\Str;

/**
 * 映画の图床（国内大厂 CDN）
 * Class Imgstop
 * @package app\ImageHosting
 * @url https://imgs.top/
 */
class Imgstop extends ImageHosting
{
    public function upload($path)
    {
        $result = Curl::get('https://imgs.top/', [], true);
        $csrfToken = preg_match('|name="csrf-token" content="(.*?)"|', $result['html'], $matches) ? $matches[1] : false;

        if (!$csrfToken) {
            Log::write('[映画の图床]获取 csrf token 失败', 'ERROR');
            return false;
        }

        $data = [
            'file' => Curl::getCurlFile($path),
            // 7=携程 15=易车，随机选一个
            'strategy_id' => [7, 15][mt_rand(0, 1)],
        ];
        $opt = [
            CURLOPT_HTTPHEADER => [
                'x-csrf-token: ' . $csrfToken,
            ],
            CURLOPT_COOKIE => $result['cookie'],
        ];
        $result = Curl::post('https://imgs.top/upload', $data, $opt);

        Log::write('[映画の图床]上传：' . json_encode(compact('data', 'opt')));
        Log::write('[映画の图床]返回：' . $result);

        $json = json_decode($result, true);
        if (!empty($json['data']['imgurl'])) {
            return $json['data']['imgurl'];
        }

        Log::write('[映画の图床]上传失败', 'ERROR');
        return false;
    }
}