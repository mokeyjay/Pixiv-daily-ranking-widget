<?php

namespace app\ImageHosting;

use app\Libs\Curl;
use app\Libs\Log;

/**
 * 京东图床
 * 鸣谢：[@metowolf](https://github.com/metowolf)
 * Class Jd
 * @package app\ImageHosting
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
            's' => 'data:image/jpeg;base64,' . base64_encode(file_get_contents($path)),
        ];
        $result = Curl::post('https://imio.jd.com/uploadfile/file/post.do', $data, [
            CURLOPT_HTTPHEADER => [
                'Accept: application/json, text/plain, */*',
                'Origin: https://jdcs.jd.com',
                'Referer: https://jdcs.jd.com/jdchat/custom.action?entry=jd_fwztc',
                'Upgrade-insecure-requests: 1'
            ],
        ]);

        $data['s'] = '（数据长度：' . strlen($data['s']) . '）';
        Log::write('[京东图床]上传：' . json_encode($data));
        Log::write('[京东图床]返回：' . $result);

        preg_match_all('|"(http.*?)"|', $result, $mathes);

        if (isset($mathes[1][0])) {
            return str_replace('http://', 'https://', $mathes[1][0]);
        }

        Log::write('[京东图床]上传失败', 'ERROR');
        return false;
    }
}