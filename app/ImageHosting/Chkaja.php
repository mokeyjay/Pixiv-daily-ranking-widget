<?php

namespace app\ImageHosting;

use app\Libs\Curl;
use app\Libs\Log;

/**
 * 愛上傳（Cloudflare）
 * Class Chkaja
 * @package app\ImageHosting
 * @url https://img.chkaja.com/
 */
class Chkaja extends ImageHosting
{
    public function upload($path)
    {
        $data = [
            'files[]' => Curl::getCurlFile($path),
            'viewpassword' => '',
            'viewtips' => '',
            'Timelimit' => '',
            'Countlimit' => '',
            'submit' => 'submit',
            'MAX_FILE_SIZE' => '8388608',
        ];
        $result = Curl::post('https://img.chkaja.com/ajaximg.php', $data);

        Log::write('[愛上傳图床]上传：' . json_encode($data));
        Log::write('[愛上傳图床]返回：' . $result);

        $url = preg_match('|https://img.chkaja.com/(.*?)\.jpg|', $result, $matches) ? $matches[0] : false;

        if (!empty($url)) {
            return $url;
        }

        Log::write('[愛上傳图床]上传失败', 'ERROR');
        return false;
    }
}