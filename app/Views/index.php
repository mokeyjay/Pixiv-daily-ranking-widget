<!-- 来自 mokeyjay 的 Pixiv每日排行榜小挂件 -->
<!-- 博客：https://www.mokeyjay.com -->
<!-- 这个博客将会集技术、ACG、日常、分享于一身。如果你喜欢，常来玩哦 -->

<!doctype html>
<html lang="zh-CN">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta name="referrer" content="no-referrer">
  <title>Pixiv 每日排行榜 Top<?= app\Libs\Config::$limit ?> 小挂件</title>

  <style>
    * { margin: 0; padding: 0; outline: none; }

    .carousel {
      overflow: hidden;
    }
    .list {
      height: 100vh;
      position: relative;
    }
    .list-item {
      position: absolute;
      width: 100%;
      display: flex;
      align-items: center;
      height: 100%;
      background-color: #fff;
    }
    img {
      width: 100vw;
    }

    .list-item.current {
      z-index: 2;
    }

    /* 翻页动画 */
    .current-to-prev {
      z-index: 1;
      animation: slide-current-to-prev .5s cubic-bezier(0.34, 0.69, 0.1, 1);
    }
    @keyframes slide-current-to-prev {
      from { transform: translateX(0) }
      to { transform: translateX(-100vw) }
    }
    .current-to-next {
      z-index: 1;
      animation: slide-current-to-next .5s cubic-bezier(0.34, 0.69, 0.1, 1);
    }
    @keyframes slide-current-to-next {
      from { transform: translateX(0) }
      to { transform: translateX(100vw) }
    }
    .next-to-current {
      z-index: 1;
      animation: slide-next-to-current .5s cubic-bezier(0.34, 0.69, 0.1, 1);
    }
    @keyframes slide-next-to-current {
      from { transform: translateX(100vw) }
      to { transform: translateX(0) }
    }
    .prev-to-current {
      z-index: 1;
      animation: slide-prev-to-current .5s cubic-bezier(0.34, 0.69, 0.1, 1);
    }
    @keyframes slide-prev-to-current {
      from { transform: translateX(-100vw) }
      to { transform: translateX(0) }
    }

    /* 左右翻页按钮 */
    .button {
      height: 100%;
      width: 70px;
      position: fixed;
      top: 0;
      z-index: 5;
      cursor: pointer;
      display: flex;
      justify-content: center;
      align-items: center;
      transition: transform .3s ease-in-out, opacity .5s ease-in-out;
      opacity: 0;
    }
    .button.prev {
      left: 0;
      transform: translateX(-70px);
    }
    .button.next {
      right: 0;
      transform: translateX(70px);
    }
    .button .arrow {
      transform: rotate(45deg);
      margin-top: -16px;
    }
    .button i {
      position: relative;
    }
    .button.prev i:before, .button.prev i:after, .button.next i:before, .button.next i:after {
      display: block;
      content: "";
      border: 4px solid white;
      border-radius: 2px;
      position: absolute;
      width: 16px;
      height: 16px;
    }
    .button.prev i:before, .button.prev i:after {
      border-top: none;
      border-right: none;
    }
    .button.next i:before, .button.next i:after {
      border-bottom: none;
      border-left: none;
    }
    .button i:before {
      filter: blur(4px);
      border-color: #777777 !important;
    }
    .carousel:hover .button {
      transform: translateX(0);
      opacity: 1;
    }

    .mask {
      height: 150px;
      width: 100%;
      position: fixed;
      bottom: 0;
      background: linear-gradient(to top, rgba(0, 0, 0, .2), transparent);
      transform: translateY(150px);
      transition: transform .3s ease-in-out
    }
    .carousel:hover .mask {
      transform: translateY(0);
    }

    /* 作品 & 作者信息 */
    .info {
      opacity: 0;
      text-shadow: black 0.1em 0.1em 0.2em;
      pointer-events: none;
      bottom: 60px;
      position: fixed;
      text-align: center;
      color: white;
      width: 100%;
      transition: opacity .3s ease-in-out;
    }
    body:hover .info { opacity: 1 }
    .title {
      font-size: 1.2rem;
      white-space: nowrap;
      text-overflow: ellipsis;
    }
    .author {
      margin-top: 5px;
      font-size: 1rem;
    }

  </style>
  <script>
      <?= app\Libs\Config::$header_script ?>
  </script>
</head>
<body>
  <div class="carousel">
    <div class="list">
      <?php foreach ($pixivJson['data'] as $k => $data): ?>
        <div class="list-item <?= $k === 0 ? 'current' : '' ?>">
          <a href="https://www.pixiv.net/artworks/<?= $data['id'] ?>" target="_blank">
            <img src="<?= $k === 0 ? $data['url'] : '' ?>" data-src="<?= $data['url'] ?>" alt="<?= htmlentities($data['title']) ?>">
            <div class="mask"></div>
            <div class="info">
              <div class="title"><?= htmlentities($data['title']) ?></div>
              <div class="author"><?= htmlentities($data['user_name']) ?></div>
            </div>
          </a>
        </div>
      <?php endforeach; ?>
    </div>
    <div class="control">
      <div class="button prev" onclick="switchPage('prev')">
        <div class="arrow"><i></i></div>
      </div>
      <div class="button next" onclick="switchPage('next')">
        <div class="arrow"><i></i></div>
      </div>
    </div>
  </div>

  <script>
    const $ = document.querySelector.bind(document);
    const $$ = document.querySelectorAll.bind(document);

    function switchPage(direction) {

      const currentDom = $('.list-item.current');
      const nextDom = findNextDomByDirection(currentDom, direction);

      [currentDom, nextDom].map(dom => {
        dom.addEventListener('animationend', function end() {
          if (dom.classList.contains('next-to-current') || dom.classList.contains('prev-to-current')) {
            dom.className = 'list-item current'
          } else {
            dom.className = 'list-item'
          }

          dom.removeEventListener('animationend', end)
        })
      })

      if (direction === 'next') {
        currentDom.className = 'list-item current-to-prev'
        nextDom.className = 'list-item next-to-current'
      } else {
        currentDom.className = 'list-item current-to-next'
        nextDom.className = 'list-item prev-to-current'
      }

      // 预加载下一张图
      loadImage(findNextDomByDirection(nextDom, direction))
    }

    // 根据翻页方向获取下一个 dom
    function findNextDomByDirection(currentDom, direction) {
      let prop = direction === 'next' ? 'next' : 'previous'
      let nextDom = currentDom[`${prop}ElementSibling`]

      // 对应方向找不到下一个 dom，就说明滑动到尽头了，要跳到开头或结尾
      if (nextDom === null) {
        prop = direction === 'next' ? 'first' : 'last'
        nextDom = $('.list').querySelector(`.list-item:${prop}-child`)
      }

      return nextDom
    }

    // 加载图片
    function loadImage() {
      for (let dom of arguments) {
        let img = dom.querySelector('img')
        img.setAttribute('src', img.getAttribute('data-src'))
      }
    }

    let interval = null
    function registerAutoPlay() {
      clearInterval(interval)
      interval = setInterval(() => switchPage('next'), 5000)
    }

    (function() {

      // 预加载前后两张图
      const currentDom = $('.list-item.current');
      loadImage(findNextDomByDirection(currentDom, 'prev'), findNextDomByDirection(currentDom, 'next'))

      registerAutoPlay()
    })()

  </script>
</body>
</html>
