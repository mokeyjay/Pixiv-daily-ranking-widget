<?php
/**
 * 项目：Pixiv每日排行榜Top50小部件
 * 作者：超能小紫(mokeyjay)
 * 博客：https://www.mokeyjay.com
 * 源码：https://github.com/mokeyjay/Pixiv-daily-top50-widget
 * 可随意修改、二次发布。但请保留上方版权声明及注明出处
 */

// 本项目所在路径
define('PX_PATH', dirname(__FILE__) . DIRECTORY_SEPARATOR);
set_time_limit(0);
error_reporting(0);

// 判断锁
$lock_file = PX_PATH . '.updatelock';
if (file_exists($lock_file)){
    // 如果是半小时前的锁，可能是下载线程挂了，判定为下载失败，继续尝试
    // 如果是半小时内的锁，则判定下载进行中，退出此线程
    $mtime = filemtime($lock_file);
    if ($mtime !== FALSE && time() - $mtime < 1800) exit;
}

require PX_PATH . 'Conf.php';
Conf::init();
require PX_PATH . 'Func.php';

// 开始下载缓存缩略图
if (Conf::$download){
    file_put_contents($lock_file, time()); // 创建锁

    if (Func::getPixivImages($image, $url) && Func::download($image) && Func::checkImage()){
        // 更新成功，保存pixiv.json
        $data = array(
            'date'  => date('Y-m-d'),
            'image' => $image[0],
            'url'   => $url[0],
        );
        Func::set('pixiv.json', $data);

        // 删除锁
        @unlink($lock_file);

        Func::clearOverdue();
    }
}