<?php
  use app\Libs\Config;
?>
<!-- 来自 mokeyjay 超能小紫 的 Pixiv每日排行榜小挂件 -->
<!-- 博客：https://www.mokeyjay.com -->
<!-- 这个博客将会集技术、ACG、日常、分享于一身，如果你喜欢，常来玩哦 -->
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Pixiv 每日排行榜 Top<?=Config::$limit?> 小挂件</title>

  <link rel="stylesheet" href="https://cdn.staticfile.org/twitter-bootstrap/3.4.1/css/bootstrap.min.css">
  <style>
    html, body, #carousel-example-generic, .carousel-inner, .item, .item div { height : 100%; }
    body { background: <?=Config::$background_color?>; }
    .item div { background-position : center; background-repeat : no-repeat; background-attachment : fixed; }
    .carousel-caption { position : static }
    .carousel-control.left, .carousel-control.right { background : none; }
  </style>
  <script>
    var _hmt = _hmt || [];
    (function() {
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
      <?php foreach ($pixivJson['image'] as $k => $v): ?>
          <?php if ($k >= Config::$limit) break; ?>
        <div class="item <?php if ($k == 0) echo 'active'; ?>">
          <a href="https://www.pixiv.net/<?= $pixivJson['url'][$k] ?>" target="_blank">
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
<script src="https://cdn.staticfile.org/jquery/2.2.4/jquery.min.js"></script>
<script src="https://cdn.staticfile.org/twitter-bootstrap/3.4.1/js/bootstrap.min.js"></script>
</body>
</html>