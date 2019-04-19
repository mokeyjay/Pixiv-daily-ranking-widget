<?php

namespace app\ImageHosting;

use app\Libs\Curl;
use app\Libs\Tools;

/**
 * 网易严选图床
 * 鸣谢：[@metowolf](https://github.com/metowolf)
 * Class Netease
 * @package app\ImageHosting
 * @url http://you.163.com/
 */
class Netease extends ImageHosting
{
    public function upload($path)
    {
        $data = ['file' => Curl::getCurlFile($path), 'format' => 'json'];
        $result = Curl::post('http://you.163.com/xhr/file/upload.json', $data, [
            CURLOPT_HTTPHEADER => [
                'Referer: http://you.163.com',
                'Origin: http://you.163.com',
            ],
        ]);

        Tools::log('[网易图床]上传：' . json_encode($data));
        Tools::log('[网易图床]返回：' . $result);

        $result = json_decode($result, true);
        if ($result['code'] != '200') {
            Tools::log('[网易图床]上传失败', 'ERROR');
            return false;
        }

        return str_replace('http://', 'https://', $result['data'][0]);
    }
}