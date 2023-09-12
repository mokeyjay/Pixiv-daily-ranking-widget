<?php

use app\Libs\Config;

?>
<!-- 来自 mokeyjay 的 Pixiv每日排行榜小挂件 -->
<!-- 博客：https://www.mokeyjay.com -->
<!-- 这个博客将会集技术、ACG、日常、分享于一身，如果你喜欢，常来玩哦 -->
<!doctype html>
<html lang="zh-CN">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta name="referrer" content="no-referrer">
  <title>Pixiv 每日排行榜 Top<?= Config::$limit ?> 小挂件</title>

  <style>
    * { margin: 0; padding: 0; }
    html, body, #container, ul, li, a { height: 100%; }
    body {
      background: <?=Config::$background_color?>;
    }

    #container {
      overflow: hidden;
    }

    /* 图片列表 & 图片 */
    ul {
      list-style: none;
      transition: ease-in-out all .5s;
    }
    li {
      float: left;
      display: flex;
      justify-content: center;
      position: relative;
      overflow: hidden;
    }
    a {
      display: flex;
      width: 100%;
      justify-content: center;
    }
    img {
      object-fit: contain;
      width: 100%;
    }

    /* 左右翻页按钮 */
    .button {
      cursor: pointer;
      position: fixed;
      top: 0;
      height: 100%;
      width: 70px;
      display: flex;
      justify-content: center;
      align-items: center;
      transition: opacity .3s ease-in-out, left .5s, right .5s;
      opacity: 0;
    }
    .button.left {
      left: -70px;
    }
    .button.right {
      right: -70px;
    }
    body:hover .button { opacity: 1; }
    body:hover .button.left { left: 0 }
    body:hover .button.right { right: 0 }

    /* 左右翻页箭头 */
    .arrow {
      transform: rotate(45deg);
      border: 4px solid white;
      border-radius: 2px;
      width: 16px;
      height: 16px;
      position: absolute;
      top: 0;
      left: 0;
    }
    .arrow.shadow {
      border-color: #777;
      filter: blur(2px);
      box-shadow: none !important;
    }
    .arrow.left {
      border-top: none;
      border-right: none;
      left: -8px;
    }
    .arrow.right {
      border-bottom: none;
      border-left: none;
      transform: rotate(45deg);
      left: -12px;
    }

    /* 阴影遮罩层 */
    .mask {
      width: 100%;
      height: 150px;
      transform: translateY(150px);
      bottom: 0;
      position: absolute;
      pointer-events: none;
      transition-duration: .5s;
      background-image: linear-gradient(transparent, rgba(0, 0, 0, 0.4));
    }
    body:hover .mask { bottom: 0; transform: translateY(0); }

    /* 作品 & 作者信息 */
    .info {
      opacity: 0;
      transition-duration: .5s;
      text-shadow: black 0.1em 0.1em 0.2em;
      pointer-events: none;
      bottom: 60px;
      position: fixed;
      text-align: center;
      color: white;
    }
    body:hover .info { opacity: 1 }
    .title {
      font-size: 1rem;
      white-space: nowrap;
      text-overflow: ellipsis;
    }
    .author {
      font-size: .75rem;
    }
  </style>
  <script>
      <?= Config::$header_script ?>
  </script>
</head>
<body>
<div id="container">
  <?php
    // 每个 li 的宽度
    $perWidth = 100 / (Config::$limit + 2);

    // 在图片列表最前面加上最后一张图、最后面加上最前一张图，确保边界情况下左右翻页动画过渡效果正常
    $firstImage = $pixivJson['data'][0];
    $lastImage = $pixivJson['data'][count($pixivJson['data']) - 1];
    $pixivJson['data'] = array_merge([$lastImage], $pixivJson['data'], [$firstImage]);
  ?>

  <ul id="list" style="width: <?= (Config::$limit + 2) * 100 ?>%; transform: translateX(-<?= $perWidth ?>%)">
    <?php foreach ($pixivJson['data'] as $k => $data): ?>
      <li data-index="<?= $k ?>" style="width: <?= $perWidth ?>%">
        <a href="https://www.pixiv.net/artworks/<?= $data['id'] ?>" target="_blank">
          <img src="<?= $k === 1 ? $data['url'] : '' ?>" data-src="<?= $data['url'] ?>" alt="<?= htmlentities($data['title']) ?>">
          <div class="mask"></div>
          <div class="info">
            <div class="title"><?= htmlentities($data['title']) ?></div>
            <div class="author"><?= htmlentities($data['user_name']) ?></div>
          </div>
        </a>
      </li>
    <?php endforeach; ?>
  </ul>
</div>

<div id="left-btn" class="button left">
  <div style="position: relative">
    <div class="arrow left shadow"></div>
    <div class="arrow left"></div>
  </div>
</div>
<div id="right-btn" class="button right">
  <div style="position: relative">
    <div class="arrow right shadow"></div>
    <div class="arrow right"></div>
  </div>
</div>

<script>
  let i        = 1;
  let maxPage  = <?= Config::$limit ?>;
  let perWidth = <?= $perWidth ?>;
  let list     = document.getElementById('list')

  function switchPage(action) {
    if(action === 'left'){
      i--;
    } else{
      i++;
    }

    list.style.transform = 'translateX(-' + (i * perWidth) + '%)';

    // 预加载下一张图
    if (i + 1 <= maxPage) {
      let nextI = [action === 'left' ? i - 1 : i + 1]
      if (i <= 0) {
        nextI = [maxPage, maxPage - 1]
      }
      loadImages(nextI)
    }

    // 翻到第 0 页了，要瞬间跳回列表最末尾，不然没法继续往左翻
    // 翻到最后一页也是同理
    if(i === 0 || i === maxPage + 1){
      setTimeout(() => {
        // 瞬间跳到最前面或最后面
        list.style.transition = 'none'
        i                     = i === 0 ? maxPage : 1
        list.style.transform  = 'translateX(-' + (i * perWidth) + '%)'
        // 重新打开动画效果
        setTimeout(() => {
          list.style.transition = 'ease-in-out all .5s'
        }, 100)
      }, 500)
    }
  }

  function loadImages(indexes) {
    for (let i of indexes) {
      let img = document.querySelector(`li[data-index='${i}'] img`)
      img.setAttribute('src', img.getAttribute('data-src'))
    }
  }

  function nextPage() {
    switchPage('right')
  }

  let interval = null

  window.addEventListener('DOMContentLoaded', () => {

    loadImages([0, 1, 2, maxPage + 1])

    // 翻页事件
    document.getElementById('left-btn').addEventListener('click', () => {
      switchPage('left')
    })
    document.getElementById('right-btn').addEventListener('click', () => {
      nextPage()
    })

    // 自动滚动
    interval = setInterval(nextPage, 5000)
    document.addEventListener('mouseenter', () => {
      clearInterval(interval)
    })
    document.addEventListener('mouseleave', () => {
      interval = setInterval(nextPage, 5000)
    })

    // 左右滑动翻页
    let startX = 0
    document.addEventListener('touchstart', e => {
      startX = e.changedTouches[0].pageX;
    });
    document.addEventListener('touchend', e => {
      let endX = e.changedTouches[0].pageX;
      let diffX = endX - startX;

      if (diffX > 50) {
        switchPage('left')
      } else if (diffX < -50) {
        nextPage()
      }
    });
  })
</script>
</body>
</html>
