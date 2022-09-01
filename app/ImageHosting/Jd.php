<?php

namespace app\ImageHosting;

use app\Libs\Curl;
use app\Libs\Log;

/**
 * 京东
 * Class Jd
 * @package app\ImageHosting
 * @url https://www.jd.com/
 */
class Jd extends ImageHosting
{
    public function upload($path)
    {
        $data = [
            'appId' => 'im.customer',
            'aid' => 'undefined',
            'clientType' => 'comet',
            'pin' => 'undefined',
            's' => 'data:image/jpg;base64,' . base64_encode(file_get_contents($path)),
        ];
        $result = Curl::post('https://imio.jd.com/uploadfile/file/post.do', $data, [
            CURLOPT_HTTPHEADER => [
                'accept: application/json, text/plain, */*',
                'referer: https://jdcs.jd.com/jdchat/custom.action?entry=jd_fwztc',
                'origin: https://jdcs.jd.com',
                'Upgrade-insecure-requests: 1',
            ],
        ]);

        Log::write('[京东]上传：' . json_encode($data));
        Log::write('[京东]返回：' . $result);

        preg_match_all('|https://(.*?)"|', $result, $result);
        if (!empty($result[1][0])) {
            return "https://{$result[1][0]}";
        }

        Log::write('[京东]上传失败', 'ERROR');
        return false;
    }
}