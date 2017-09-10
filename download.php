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

require PX_PATH . 'Conf.php';
Conf::init();
require PX_PATH . 'Func.php';

// 开始下载缓存缩略图
if ((Conf::$download || Conf::$url_cache) && Func::createLock()){
    if (Func::getPixivImages($image, $url) && Func::download($image) && Func::checkImage()){
        // 更新成功，保存pixiv.json
        $data = array(
            'date'  => date('Y-m-d'),
            'image' => $image[0],
            'url'   => $url[0],
        );
        Func::set('pixiv.json', $data);
        Func::clearOverdue();

        // 删除锁
        @unlink(PX_PATH . '.updatelock');
    }
}