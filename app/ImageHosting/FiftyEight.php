<?php

namespace app\ImageHosting;

use app\Libs\Curl;
use app\Libs\Log;

/**
 * 58 同城图床（有时会和谐一些图）
 * 鸣谢：[@metowolf](https://github.com/metowolf)
 * Class FiftyEight
 * @package app\ImageHosting
 */
class FiftyEight extends ImageHosting
{
    public function upload($path)
    {
        $data = [
            'Pic-Size' => '0*0',
            'Pic-Encoding' => 'base64',
            'Pic-Path' => '/nowater/webim/big/',
            'Pic-Data' => base64_encode(file_get_contents($path)),
        ];
        $result = Curl::post('https://upload.58cdn.com.cn/json', json_encode($data), [
            CURLOPT_HTTPHEADER => [
                'Origin: https://ai.58.com',
                'Referer: https://ai.58.com/pc/'
            ],
        ]);

        $data['Pic-Data'] = '（数据长度：' . strlen($data['Pic-Data']) . '）';
        Log::write('[58图床]上传：' . json_encode($data));
        Log::write('[58图床]返回：' . $result);

        if (empty($result) || stripos($result, 'n_v2') !== 0) {
            Log::write('[58图床]上传失败', 'ERROR');
            return false;
        }

        return 'https://pic3.58cdn.com.cn/nowater/webim/big/' . $result;
    }
}