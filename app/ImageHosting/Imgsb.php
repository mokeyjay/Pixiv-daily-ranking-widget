<?php

namespace app\ImageHosting;

use app\Libs\Curl;
use app\Libs\Tools;

/**
 * img.sb图床
 * P.S.船新的V2版本接口还没开放，所以目前跟Smms是完全一样的
 * 鸣谢：[@Showfom](https://github.com/Showfom)
 * Class Imgsb
 * @package app\ImageHosting
 * @url https://img.sb
 */
class Imgsb extends ImageHosting
{
    public function upload($path)
    {
        $data = ['smfile' => Curl::getCurlFile($path)];
        $result = Curl::post('https://sm.ms/api/upload', $data);

        Tools::log('[Imgsb图床]上传：' . json_encode($data));
        Tools::log('[Imgsb图床]返回：' . $result);
        $result = json_decode($result, true);

        if (isset($result['code']) && $result['code'] == 'success') {
            return $result['data']['url'];
        } else {
            Tools::log('[Imgsb图床]上传失败：' . $result['msg'], 'ERROR');
            return false;
        }
    }
}