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
    body { background-color : <?= app\Libs\Config::$background_color ?>; }
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
      visibility: hidden;
    }
    img {
      width: 100vw;
    }

    .list-item.current {
      z-index: 2;
      visibility: visible;
    }

    /* 翻页动画 */
    .current-to-prev, .current-to-next, .next-to-current, .prev-to-current {
      z-index: 1;
      visibility: visible;
    }
    .current-to-prev {
      animation: slide-current-to-prev .5s cubic-bezier(0.34, 0.69, 0.1, 1);
    }
    @keyframes slide-current-to-prev {
      from { transform: translateX(0) }
      to { transform: translateX(-100vw) }
    }
    .current-to-next {
      animation: slide-current-to-next .5s cubic-bezier(0.34, 0.69, 0.1, 1);
    }
    @keyframes slide-current-to-next {
      from { transform: translateX(0) }
      to { transform: translateX(100vw) }
    }
    .next-to-current {
      animation: slide-next-to-current .5s cubic-bezier(0.34, 0.69, 0.1, 1);
    }
    @keyframes slide-next-to-current {
      from { transform: translateX(100vw) }
      to { transform: translateX(0) }
    }
    .prev-to-current {
      animation: slide-prev-to-current .5s cubic-bezier(0.34, 0.69, 0.1, 1);
    }
    @keyframes slide-prev-to-current {
      from { transform: translateX(-100vw) }
      to { transform: translateX(0) }
    }

    /* 左右翻页按钮 */
    .button {
      height: 100%;
      width: 50px;
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
      box-sizing: border-box;
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
      background: linear-gradient(to top, rgba(0, 0, 0, .3), transparent);
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
      width: 80%;
      transition: opacity .3s ease-in-out;
      transform: translateX(10%);
    }
    .carousel:hover .info { opacity: 1 }
    .title, .author {
      white-space: nowrap;
      text-overflow: ellipsis;
      overflow: hidden;
    }
    .title {
      font-size: 1rem;
    }
    .author {
      margin-top: 5px;
      font-size: .75rem;
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
      <div class="button prev" onclick="carousel.prev()">
        <div class="arrow"><i></i></div>
      </div>
      <div class="button next" onclick="carousel.next()">
        <div class="arrow"><i></i></div>
      </div>
    </div>
  </div>

  <script>
    const $ = document.querySelector.bind(document);
    const $$ = document.querySelectorAll.bind(document);

    class Carousel {
      // 自动播放句柄
      autoPlayInterval = 0;
      // 滑动手势开始位置
      startX = 0;

      constructor($dom) {
        // 轮播组件
        this.$carousel = $dom
        // 轮播列表
        this.$carouselList = this.$carousel.querySelector('.list')
        // 当前展示项
        this.$currentItem = this.$carouselList.querySelector('.list-item.current')
      }

      init() {
        this.loadImage(
          this.findNextItemByDirection(this.$currentItem, 'prev'),
          this.findNextItemByDirection(this.$currentItem, 'next')
        )

        this.registerAutoPlay()
        this.registerMouseHoverPausePlay()
        this.registerSlideGesture()
      }

      // 根据翻页方向获取下一个项目
      findNextItemByDirection($item, direction) {
        let prop = direction === 'next' ? 'next' : 'previous'
        let nextItem = $item[`${prop}ElementSibling`]

        // 对应方向找不到下一个 item，就说明滑动到尽头了，要跳到开头或结尾
        if (nextItem === null) {
          prop = direction === 'next' ? 'first' : 'last'
          nextItem = this.$carouselList.querySelector(`.list-item:${prop}-child`)
        }

        return nextItem
      }

      // 翻页
      switchPage(direction) {
        const $nextItem = this.findNextItemByDirection(this.$currentItem, direction);

        [this.$currentItem, $nextItem].map($item => {
          $item.addEventListener('animationend', function end() {
            if ($item.classList.contains('next-to-current') || $item.classList.contains('prev-to-current')) {
              $item.className = 'list-item current'
            } else {
              $item.className = 'list-item'
            }

            $item.removeEventListener('animationend', end)
          })
        })

        if (direction === 'next') {
          this.$currentItem.className = 'list-item current-to-prev'
          $nextItem.className = 'list-item next-to-current'
        } else {
          this.$currentItem.className = 'list-item current-to-next'
          $nextItem.className = 'list-item prev-to-current'
        }

        // 预加载下一张图
        this.loadImage(this.findNextItemByDirection($nextItem, direction))

        this.$currentItem = $nextItem
      }

      // 加载图片
      loadImage() {
        for (let $item of arguments) {
          let $img = $item.querySelector('img')
          if ($img.getAttribute('src') === '') {
            $img.setAttribute('src', $img.getAttribute('data-src'))
          }
        }
      }

      next() {
        this.switchPage('next')
      }
      prev() {
        this.switchPage('prev')
      }

      // 注册自动播放
      registerAutoPlay() {
        clearInterval(this.autoPlayInterval)
        this.autoPlayInterval = setInterval(() => this.next(), 5000)
      }

      // 注册鼠标移入暂停轮播
      registerMouseHoverPausePlay() {
        this.$carousel.addEventListener('pointerenter', () => clearInterval(this.autoPlayInterval))
        this.$carousel.addEventListener('pointerleave', () => this.registerAutoPlay())
      }

      // 注册左右滑动手势
      registerSlideGesture() {
        this.$carousel.addEventListener('touchstart', e => this.startX = e.changedTouches[0].pageX);
        this.$carousel.addEventListener('touchend', e => {
          let endX = e.changedTouches[0].pageX;
          let diffX = endX - this.startX;

          if (diffX > 50) {
            this.prev()
          } else if (diffX < -50) {
            this.next()
          }
        });
      }
    }

    const carousel = new Carousel($('.carousel'));
    carousel.init()

  </script>
</body>
</html>
