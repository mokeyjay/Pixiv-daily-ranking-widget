<?php

namespace app\ImageHosting;

use app\Libs\Curl;
use app\Libs\Log;

/**
 * 猫盒
 * Class Catbox
 * @package app\ImageHosting
 * @url https://catbox.moe/
 */
class Catbox extends ImageHosting
{
    public function upload($path)
    {
        $data = [
            'reqtype' => 'fileupload',
            'userhash' => '',
            'fileToUpload' => Curl::getCurlFile($path),
        ];
        $result = Curl::post('https://catbox.moe/user/api.php', $data);

        Log::write('[猫盒图床]上传：' . json_encode($data));
        Log::write('[猫盒图床]返回：' . $result);

        if (stripos($result, 'files.catbox.moe') !== false) {
            return $result;
        }

        Log::write('[猫盒图床]上传失败', 'ERROR');
        return false;
    }
}