<?php

namespace app\ImageHosting;

use app\Libs\Config;
use app\Libs\Log;

/**
 * 本地存储
 * Class Local
 * @package app\ImageHosting
 * @url https://www.mokeyjay.com
 */
class Local extends ImageHosting
{
    public function upload($path)
    {
        $fileName = pathinfo($path, PATHINFO_BASENAME);
        $file = STORAGE_PATH . 'images/' . $fileName;
        Log::write('[本地]目标：' . $path);
        Log::write('[本地]存储到：' . $file);

        if (!file_put_contents($file, file_get_contents($path))) {
            Log::write('[本地]存储失败', 'ERROR');
            return false;
        }

        return Config::$url . 'storage/images/' . $fileName;
    }
}