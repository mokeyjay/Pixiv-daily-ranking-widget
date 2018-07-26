<?php
/**
 * 项目：Pixiv每日排行榜Top50小部件
 * 版本：2.9
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

// 获取缓存文件内容
$json = Func::get('pixiv.json');
// 缓存是否有效。无效则刷新
if ( !empty($json['date']) && $json['date'] == date('Y-m-d') && !empty($json['image']) && !empty($json['url'])){
    $image = $json['image'];
    $url = $json['url'];
} else {
    if (Func::getPixivImages($image, $url) === FALSE){
        header('Location: ' . Conf::$url);
        exit;
    }

    $image = $image[0];
    $url = $url[0];
    // 启动下载线程
    if ((Conf::$download || Conf::$url_cache) && Func::checkLock() == FALSE){
        Func::downloadThread();
    }
}
?>

<!-- 来自 超能小紫 的 Pixiv每日榜Top50挂件 -->
<!-- 博客：https://www.mokeyjay.com -->
<!-- 这个博客将会集技术、ACG、日常、分享于一身，如果你喜欢，常来玩哦 -->
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pixiv每日榜Top50挂件</title>

    <link rel="stylesheet" href="//cdn.staticfile.org/todc-bootstrap/3.3.7-3.3.7/css/bootstrap.min.css">
    <style>
        html, body, #carousel-example-generic, .carousel-inner, .item, .item div { height : 100%; }

        .item { background-color : #<?=Conf::$background_color?>; }

        .item div { background-position : center; background-repeat : no-repeat; background-attachment : fixed; }

        .carousel-caption { position : static }

        .carousel-control.left, .carousel-control.right { background : none; }
    </style>
    <script>
        var _hmt = _hmt || [];
        (function(){
            var hm = document.createElement("script");
            hm.src = "https://hm.baidu.com/hm.js?65a9f8c5bfa2055dbb44f895cb5ea399";
            var s  = document.getElementsByTagName("script")[0];
            s.parentNode.insertBefore(hm, s);
        })();
    </script>
</head>
<body>
<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">

    <div class="carousel-inner" role="listbox">
        <?php foreach ($image as $k => $v): ?>
            <?php if ($k >= Conf::$limit) break; ?>
            <div class="item <?php if ($k == 0) echo 'active'; ?>">
                <a href="http://www.pixiv.net/<?= $url[$k] ?>" target="_blank">
                    <div class="carousel-caption" style="background-image: url(<?= str_replace('http://i', '//i', $v) ?>)"></div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>

    <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>
<script src="//cdn.staticfile.org/jquery/2.2.4/jquery.min.js"></script>
<script src="//cdn.staticfile.org/todc-bootstrap/3.3.7-3.3.7/js/bootstrap.min.js"></script>
</body>
</html>