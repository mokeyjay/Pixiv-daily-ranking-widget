<?php
  use app\Libs\Config;
?>
<!-- 来自 mokeyjay 的 Pixiv每日排行榜小挂件 -->
<!-- 博客：https://www.mokeyjay.com -->
<!-- 这个博客将会集技术、ACG、日常、分享于一身，如果你喜欢，常来玩哦 -->
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="referrer" content="no-referrer">
  <title>Pixiv 每日排行榜 Top<?=Config::$limit?> 小挂件</title>

  <link rel="stylesheet" href="https://lf6-cdn-tos.bytecdntp.com/cdn/expire-1-M/bootstrap/5.1.3/css/bootstrap.min.css">
  <style>
    body { background: <?=Config::$background_color?>; }

    html, body, #carouselExampleControls, .carousel-inner, .carousel-item, .carousel-item a, .carousel-item a div { height: 100%; }

    /* 图片样式 */
    .carousel-item a div {
      background-position : center;
      background-repeat : no-repeat;
      background-size: contain;
    }

    /* 左右翻页箭头 */
    .arrow {
      transform: rotate(45deg);
      border: 4px solid white;
      border-radius: 2px;
      width: 16px;
      height: 16px;
      position: fixed;
      transition: opacity .3s ease-in-out, left .5s, right .5s;
      top: 50%;
      margin-top: -9px;
      opacity: 0;
    }
    .arrow.shadow {
      border-color: #777;
      filter: blur(2px);
      box-shadow: none !important;
    }
    .arrow.left {
      border-top: none;
      border-right: none;
      left: -12%;
    }
    .arrow.right {
      border-bottom: none;
      border-left: none;
      transform: rotate(45deg);
      right: -12%;
    }
    .carousel:hover .arrow { opacity: 1; }
    .carousel:hover .arrow.left { left: 6%; }
    .carousel:hover .arrow.right { right: 6%; }

    /* 隐藏 bs 自带的箭头 */
    span[class^="carousel-control-"] { background: none; }
    button[class^="carousel-control-"] { opacity: 1; }

      /* 底部信息栏 */
    .carousel-caption {
      opacity: 0;
      transition-duration: .5s;
      text-shadow: black 0.1em 0.1em 0.2em;
      pointer-events:none;
      bottom: 0;
      z-index: 10;
    }
    .carousel-caption h5 {
      font-size: 1rem;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
    .carousel-caption p { font-size: .75rem; }
    .carousel:hover .carousel-caption { opacity: 1; }

    /* 底部信息栏 - 阴影层 */
    #mask {
      width: 100%;
      height: 150px;
      bottom: -150px;
      position: fixed;
      pointer-events:none;
      transition-duration: .5s;
      background-image: linear-gradient(transparent, rgba(0,0,0,0.4));
    }
    .carousel:hover #mask { bottom: 0; }
  </style>
  <script>
    var _hmt = _hmt || [];
    (function() {
      var hm = document.createElement("script");
      hm.src = "https://hm.baidu.com/hm.js?65a9f8c5bfa2055dbb44f895cb5ea399";
      var s = document.getElementsByTagName("script")[0];
      s.parentNode.insertBefore(hm, s);
    })();
  </script>
</head>
<body>
<div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
  <div class="carousel-inner">
      <?php foreach ($pixivJson['data'] as $k => $data): ?>
        <?php if ($k >= Config::$limit) break; ?>
        <div class="carousel-item <?php if ($k == 0) echo 'active'; ?>">
          <a href="https://www.pixiv.net/artworks/<?= $data['id'] ?>" target="_blank" style="display: block">
            <div style="background-image: url(<?= $data['url'] ?>)"></div>
          </a>
          <div class="carousel-caption d-md-block">
            <h5><?= $data['title'] ?></h5>
            <p><?= $data['user_name'] ?></p>
          </div>
        </div>
      <?php endforeach; ?>
  </div>

  <div id="mask"></div>

  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true">
      <div class="arrow left shadow"></div>
      <div class="arrow left"></div>
    </span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true">
      <div class="arrow right shadow"></div>
      <div class="arrow right"></div>
    </span>
  </button>
</div>
<script src="https://lf3-cdn-tos.bytecdntp.com/cdn/expire-1-M/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>