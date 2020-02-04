<?php

namespace app\ImageHosting;

use app\Libs\Config;
use app\Libs\Curl;
use app\Libs\Tools;

/**
 * sm.ms图床
 * 鸣谢：[@Showfom](https://github.com/Showfom)
 * Class Smms
 * @package app\ImageHosting
 * @url https://sm.ms
 */
class Smms extends ImageHosting
{
    public function upload($path)
    {
        if (empty(Config::$image_hosting_extend['smms']['token'])) {
            Tools::log('[Smms图床]上传失败：请先配置 smms 的 token', 'ERROR');
            return false;
        }

        $data = ['smfile' => Curl::getCurlFile($path)];
        $header = [
            CURLOPT_HTTPHEADER => [
                'Authorization' => Config::$image_hosting_extend['smms']['token'],
            ],
        ];
        $result = Curl::post('https://sm.ms/api/v2/upload', $data, $header);

        Tools::log('[Smms图床]上传：' . json_encode($data));
        Tools::log('[Smms图床]返回：' . $result);
        $result = json_decode($result, true);

        if (isset($result['code'])) {
            if ($result['code'] == 'success') {
                return $result['data']['url'];
            } elseif ($result['code'] == 'image_repeated') {
                return $result['images'];
            }
        }

        Tools::log('[Smms图床]上传失败：' . $result['msg'], 'ERROR');
        return false;
    }
}