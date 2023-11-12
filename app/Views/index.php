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
      transition: ease-in-out transform .5s;
    }
    li {
      float: left;
      position: relative;
      overflow: hidden;
    }
    a {
      display: flex;
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
      transition: ease-in-out opacity .3s,
                  transform .5s;
      opacity: 0;
    }
    .button.prev {
      left: -70px;
    }
    .button.next {
      right: -70px;
    }
    body:hover .button { opacity: 1; }
    body:hover .button.prev { transform: translateX(70px); }
    body:hover .button.next { transform: translateX(-70px); }
    /* 左右箭头 */
    .button i {
      position: relative;
      display: block;
      width: 30px;
      height: 30px;
    }
    .button i::before, .button i::after {
      display: block;
      content: " ";
      width: 16px;
      height: 16px;
      border: 4px solid white;
      border-radius: 2px;
      position: absolute;
      border-bottom: none;
      border-left: none;
      transform: rotate(45deg);
    }
    .button i::before {
      border-color: #777;
      filter: blur(2px);
    }
    .button.prev i::before, .button.prev i::after {
      transform: rotate(225deg);
      left: 10px;
    }
    /*.button.prev i::before, .button.prev i::after {*/
    /*  border-top: none;*/
    /*  border-right: none;*/
    /*}*/

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

    $pixivJson['data'] = array_slice($pixivJson['data'], 0, Config::$limit);

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

  <div class="button prev" onclick="switchPage('left')"><i></i></div>
  <div class="button next" onclick="nextPage()"><i></i></div>
</div>

<script>
  let page    = 1;
  let maxPage = <?= Config::$limit ?>;
  let perWidth = <?= $perWidth ?>;
  let list     = document.getElementById('list')

  // 加载图片
  function loadImages(indexes) {
    for (let i of indexes) {
      let img = document.querySelector(`li[data-index='${i}'] img`)
      img && img.setAttribute('src', img.getAttribute('data-src'))
    }
  }

  // 翻页锁
  let switchLock = false
  // 翻页
  function switchPage(action) {
    if (switchLock) return

    switchLock = true
    action === 'left' ? page-- : page++;

    // 图片预加载
    // 一般情况下只需要根据翻页方向预加载下一页就够了
    if (page >= 1 && page <= maxPage) {
      loadImages([action === 'left' ? page - 1 : page + 1])
    } else if (page === 0) {
      // 但在左翻到第 0 页（即排行榜最后一张图）时，需要同时预加载最后一张图和倒数第二张图
      loadImages([maxPage, maxPage - 1])
    }

    list.style.transform = 'translateX(-' + (page * perWidth) + '%)';

    // 如果列表已经达到边界，就重置列表位置
    if (page === 0 || page === maxPage + 1) {
      list.addEventListener('transitionend', resetListPosition)
    }
  }
  list.addEventListener('transitionend', () => switchLock = false)

  // 重置列表位置
  function resetListPosition() {
    // 翻到第 0 页了，要关闭动画瞬间跳回列表最末尾，不然没法继续往左翻
    // 翻到最后一页也是同理
    page = page === 0 ? maxPage : 1
    list.style.transition = 'none'
    list.style.transform = 'translateX(-' + (page * perWidth) + '%)'

    setTimeout(() => {
      list.style.transition = 'ease-in-out all .5s'
    })

    list.removeEventListener('transitionend', resetListPosition)
  }

  function nextPage() {
    switchPage('right')
  }

  // 自动播放
  let interval = null
  function startAutoPlay() {
    clearInterval(interval)
    interval = setInterval(nextPage, 5000)
  }

  window.addEventListener('DOMContentLoaded', () => {

    loadImages([0, 1, 2, maxPage + 1])

    // 注册自动滚动
    startAutoPlay()
    document.addEventListener('pointerenter', () => clearInterval(interval))
    document.addEventListener('pointerleave', startAutoPlay)

    // 左右滑动手势
    let startX = 0
    document.addEventListener('touchstart', e => startX = e.changedTouches[0].pageX);
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
