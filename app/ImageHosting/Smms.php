<?php

namespace app\ImageHosting;

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
        $data = ['smfile' => Curl::getCurlFile($path)];
        $result = Curl::post('https://sm.ms/api/upload', $data);

        Tools::log('[Smms图床]上传：' . json_encode($data));
        Tools::log('[Smms图床]返回：' . $result);
        $result = json_decode($result, true);

        if (isset($result['code']) && $result['code'] == 'success') {
            return $result['data']['url'];
        } else {
            Tools::log('[Smms图床]上传失败：' . $result['msg'], 'ERROR');
            return false;
        }
    }
}