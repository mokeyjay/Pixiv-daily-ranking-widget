<?php

namespace app\ImageHosting;

use app\Libs\Config;
use app\Libs\Curl;
use app\Libs\Log;

/**
 * tietuku图床
 * P.S.不怎么建议使用贴图库，小毛病挺多的
 * Class Tietuku
 * @package app\ImageHosting
 * @url http://www.tietuku.com
 */
class Tietuku extends ImageHosting
{
    public function upload($path)
    {
        if (empty(Config::$image_hosting_extend['tietuku']['token'])) {
            Log::write('[Tietuku图床]上传失败：请先配置 tietuku 的 token', 'ERROR');
            return false;
        }

        $data = [
            'Token' => Config::$image_hosting_extend['tietuku']['token'],
            'file'  => Curl::getCurlFile($path),
        ];
        $result = Curl::post('http://up.imgapi.com/', $data);

        Log::write('[Tietuku图床]上传：' . json_encode($data));
        Log::write('[Tietuku图床]返回：' . $result);
        $result = json_decode($result, true);

        if (isset($result['code'])) {
            Log::write('[Tietuku图床]上传失败：' . $result['info'], 'ERROR');
            return false;
        }
        return $result['linkurl'];
    }
}