<?php

namespace app\ImageHosting;

use app\Libs\Config;
use app\Libs\Tools;

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
        Tools::log('[本地]目标：' . $path);
        Tools::log('[本地]存储到：' . $file);

        if (file_put_contents($file, file_get_contents($path)) === false) {
            Tools::log('[本地]存储失败', 'ERROR');
            return false;
        }
        return Config::$url . 'storage/images/' . $fileName;
    }
}