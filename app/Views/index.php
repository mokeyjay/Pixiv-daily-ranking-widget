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

  <link rel="stylesheet" href="https://cdn.staticfile.org/bootstrap/5.1.3/css/bootstrap.min.css">
  <style>
    body { background: <?=Config::$background_color?>; }

    html, body, #carouselExampleControls, .carousel-inner, .carousel-item, .carousel-item a, .carousel-item div { height: 100%; }
    .carousel-item div { background-position : center; background-repeat : no-repeat; background-attachment : fixed; }
    .carousel-control-prev-icon, .carousel-control-next-icon { opacity: 0; transition-duration: .5s; }
    .carousel-control-prev-icon:hover, .carousel-control-next-icon:hover { opacity: 1; }
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
<div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel" data-bs-interval="false">
  <div class="carousel-inner">
      <?php foreach ($pixivJson['image'] as $k => $v): ?>
        <?php if ($k >= Config::$limit) break; ?>
        <div class="carousel-item <?php if ($k == 0) echo 'active'; ?>">
          <a href="https://www.pixiv.net/<?= $pixivJson['url'][$k] ?>" target="_blank" style="display: block">
            <div style="background-image: url(<?= str_replace('http://i', '//i', $v) ?>)"></div>
          </a>
        </div>
      <?php endforeach; ?>
  </div>

  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>
<script src="https://cdn.staticfile.org/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>