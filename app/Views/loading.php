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
  <title>Pixiv 每日排行榜 Top<?=Config::$limit?> 小挂件</title>
  <style>
    html, body { height : 100%; background-color : <?=Config::$background_color?>; }
    body { display: flex; align-items: center; justify-content: center; margin: 0; }
    div { text-align : center; }

    @keyframes spinner-two-alt {
      0% {transform: rotate(0deg)}
      to {transform: rotate(359deg)}
    }

    .gg-spinner-two-alt,.gg-spinner-two-alt::before {
      box-sizing: border-box;
      display: block;
      width: 40px;
      height: 40px;
      color: #aaaaaa;
    }

    .gg-spinner-two-alt {
      position: relative;
      margin: 20px auto;
    }

    .gg-spinner-two-alt::before {
      content: "";
      position: absolute;
      border-radius: 100px;
      animation: spinner-two-alt 1s cubic-bezier(.6,0,.4,1) infinite;
      border: 6px solid transparent;
      border-bottom-color: currentColor;
      border-top-color: currentColor
    }

    .tip { font-size: 22px; color: #aaaaaa; }
  </style>
  <script>
    setTimeout(function() {
      location.reload()
    }, 30000)
  </script>
</head>
<body>
<div>
  <i class="gg-spinner-two-alt"></i>
  <div class="tip">排行榜更新中<br>请稍候</div>
</div>
</body>
<script>
  window.onload = function(){
    if (navigator.language.substring(0, 2).toLowerCase() !== 'zh') {
      document.querySelector('div.tip').innerHTML = 'Ranking Updating<br>Just a moment'
    }
  }
</script>
</html>