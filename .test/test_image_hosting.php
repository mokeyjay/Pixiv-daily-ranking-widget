<?php

use app\Libs\Config;
use app\ImageHosting\ImageHosting;

define("BASE_PATH", dirname(__FILE__, 2) . DIRECTORY_SEPARATOR);
const APP_PATH = BASE_PATH . 'app' . DIRECTORY_SEPARATOR;
const STORAGE_PATH = BASE_PATH . 'storage' . DIRECTORY_SEPARATOR;
const IS_CLI = PHP_SAPI === 'cli';
const TEST_PATH = BASE_PATH . '.test' . DIRECTORY_SEPARATOR;

require APP_PATH . 'autoload.php';

Config::init();

$skip = ['Riyugo', 'Smms', 'Tietuku', 'ImageHosting', 'Local', '.', '..'];
foreach (scandir(APP_PATH . 'ImageHosting') as $fileName) {

    $className = pathinfo($fileName, PATHINFO_FILENAME);
    if (in_array($className, $skip) || empty($className)) {
        continue;
    }

    $imageHosting = ImageHosting::make($className);
    if ($url = $imageHosting->upload(TEST_PATH . 'pic.jpg')) {
        var_dump($className . '成功： ' . $url);
    } else {
        var_dump($className . '失败： ' . $url);
    }
}