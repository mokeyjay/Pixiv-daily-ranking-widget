<?php

namespace app\Controllers;

use app\Libs\Config;
use app\Libs\Lock;
use app\Libs\Pixiv;
use app\Libs\Storage;
use app\Libs\Tools;

class IndexController extends Controller
{
    public function index()
    {
        $pixivJson = Storage::getJson('pixiv');
        if ($pixivJson === false || !Pixiv::checkDate($pixivJson)) {
            if (Lock::create('refresh', 600) && Config::$disable_web_job === false) {
                Tools::runRefreshThread();
            }
        }

        if ($pixivJson === false) {
            include APP_PATH . 'Views/loading.php';
        } else {
            require APP_PATH . 'Views/index.php';
        }
    }
}