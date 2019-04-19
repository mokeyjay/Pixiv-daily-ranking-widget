<?php

namespace app\ImageHosting;

use app\Libs\Curl;
use app\Libs\Tools;

/**
 * 苏宁图床
 * 鸣谢：[@metowolf](https://github.com/metowolf)
 * Class Suning
 * @package app\ImageHosting
 * @url https://www.suning.com/
 */
class Suning extends ImageHosting
{
    public function upload($path)
    {
        $data = [
            'Filedata'       => Curl::getCurlFile($path),
            'omsOrderItemId' => 1,
            'custNum'        => 1,
            'deviceType'     => 1,
        ];
        $result = Curl::post('http://review.suning.com/imageload/uploadImg.do', $data, [
            CURLOPT_HTTPHEADER => [
                'Referer: http://review.suning.com/my_cmmdty_review.do',
            ],
        ]);

        Tools::log('[苏宁图床]上传：' . json_encode($data));
        Tools::log('[苏宁图床]返回：' . $result);

        $result = json_decode($result, true);
        if ($result['errorcode'] != '1') {
            Tools::log('[苏宁图床]上传失败', 'ERROR');
            return false;
        }

        return "https:{$result['src']}.jpg";
    }
}