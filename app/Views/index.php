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

    html, body, #carouselExampleControls, .carousel-inner, .carousel-item, .carousel-item a, .carousel-item a div { height: 100%; }

    .carousel-item a div {
      background-position : center;
      background-repeat : no-repeat;
      background-attachment : fixed;
    }

    button[class^="carousel-control-"], .carousel-caption { transition-duration: .5s; }
    .carousel:hover button[class^="carousel-control-"], .carousel:hover .carousel-caption { opacity: 1; }

    button[class^="carousel-control-"] { position: fixed; transition-property: left, right; }
    .carousel-control-prev { left: -36px; }
    .carousel-control-next { right: -36px; }
    .carousel:hover .carousel-control-prev { left: 0; }
    .carousel:hover .carousel-control-next { right: 0; }

    .carousel-caption {
      opacity: 0;
      text-shadow: black 0.1em 0.1em 0.2em;
      z-index: 10;
      pointer-events:none;
    }
    .carousel-caption h5 {
      font-size: 1rem;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
    .carousel-caption p { font-size: .75rem; }

    #mask {
      width: 100%;
      height: 150px;
      bottom: -150px;
      position: fixed;
      pointer-events:none;
      transition-duration: .5s;
      background-image: linear-gradient(transparent, rgba(0,0,0,0.4));
    }
    #carouselExampleControls:hover #mask {
      bottom: 0;
    }
  </style>
  <!-- Global site tag (gtag.js) - Google Analytics -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=G-P9HFSFJQ02"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-P9HFSFJQ02');
  </script>
</head>
<body>
<div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel" data-bs-interval="false">
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