<?php

namespace app\Controllers;

use app\Libs\Config;
use app\Libs\Lock;
use app\Libs\Pixiv;
use app\Libs\Request;
use app\Libs\Storage;

class IndexController extends Controller
{
    public function index()
    {
        $pixivJson = Storage::getJson('pixiv');
        if ($pixivJson === false || !Pixiv::checkDate($pixivJson)) {
            if (Lock::create('refresh', 600) && Config::$disable_web_job === false) {
                Request::execRefreshThread();
            }
        }

        if ($pixivJson === false) {
            include APP_PATH . 'Views/loading.php';
        } else {
            $pixivJson['data'] = array_slice($pixivJson['data'], 0, Config::$limit);

            require APP_PATH . 'Views/index.php';
        }
    }
}