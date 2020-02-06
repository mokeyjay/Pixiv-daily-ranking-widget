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
    html, body { height : 100%; background-color : #<?=Config::$background_color?>; }

    body { display : flex; align-items : center; justify-content : center; }

    div { text-align : center; }

    .glyphicon { display : block; font-size : 48px; color : #888888; margin-bottom : 20px; }

    .tip { font-size : 22px; color : #aaaaaa; }

    .circle { animation : circle 3s infinite linear; }

    @-webkit-keyframes circle {
      0% { transform : rotate(0deg); }
      100% { transform : rotate(360deg); }
    }
  </style>
  <script>
    setTimeout(function() {
      location.reload()
    }, 10000)
  </script>
</head>
<body>
<div>
  <span class="glyphicon glyphicon-refresh circle" aria-hidden="true"></span>
  <div class="tip">排行榜更新中<br>请稍候</div>
</div>
</body>
<script src="https://cdn.staticfile.org/jquery/2.2.4/jquery.min.js"></script>
<script src="https://cdn.staticfile.org/twitter-bootstrap/3.4.1/js/bootstrap.min.js"></script>
</html>