<?php
/**
 * 项目：Pixiv每日排行榜Top50小部件
 * 版本：4.4
 * 作者：超能小紫(mokeyjay)
 * 博客：https://www.mokeyjay.com
 * 源码：https://github.com/mokeyjay/Pixiv-daily-ranking-widget
 * 可随意修改、二次发布。但请保留上方版权声明及注明出处
 */

define('BASE_PATH', dirname(__FILE__) . DIRECTORY_SEPARATOR);
define('APP_PATH', BASE_PATH . 'app' . DIRECTORY_SEPARATOR);
define('STORAGE_PATH', BASE_PATH . 'storage' . DIRECTORY_SEPARATOR);
define('IS_CLI', PHP_SAPI === 'cli');

require APP_PATH . 'autoload.php';
app\App::run();