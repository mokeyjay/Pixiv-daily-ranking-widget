<?php

namespace app\ImageHosting;

use app\Libs\Curl;
use app\Libs\Tools;

/**
 * 京东图床
 * 鸣谢：[@metowolf](https://github.com/metowolf)
 * Class Jd
 * @package app\ImageHosting
 * @url https://www.jd.com
 */
class Jd extends ImageHosting
{
    public function upload($path)
    {
        $data = ['file' => Curl::getCurlFile($path)];
        $result = Curl::post('https://search.jd.com/image?op=upload', $data, [
            CURLOPT_HTTPHEADER => [
                'Referer: https://www.jd.com/',
            ],
        ]);

        Tools::log('[京东图床]上传：' . json_encode($data));
        Tools::log('[京东图床]返回：' . $result);

        preg_match('/callback\("(jfs.*)"\);/', $result, $match);
        if (count($match) != 2) {
            Tools::log('[京东图床]上传失败', 'ERROR');
            return false;
        }

        $cdn = [1, 10, 11, 12, 13, 14, 20, 30];
        $randKey = array_rand($cdn);
        return "https://img{$cdn[$randKey]}.360buyimg.com/img/{$match[1]}";
    }
}