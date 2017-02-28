<?php
/**
 * 项目：Pixiv每日排行榜Top50小部件
 * 作者：超能小紫(mokeyjay)
 * 博客：http://www.mokeyjay.com
 * 源码：http://git.oschina.net/mokeyjay/Pixiv-daily-top50-widget
 * 可随意修改、二次发布。但请保留上方版权声明及注明出处
 */

/**
 * 静态方法类
 * Class Func
 */
class Func
{
    /**
     * 获取文件内容。失败返回空数组
     * @param string $file
     * @return array
     */
    public static function get($file)
    {
        if (is_readable($file)){
            $file = @file_get_contents(PX_PATH . $file);
            if (empty($file)) return array();

            $file = json_decode($file, TRUE);
            return empty($file) ? array() : $file;
        } else {
            return array();
        }
    }

    /**
     * 保存文件内容
     * @param string       $file
     * @param string|array $content
     * @return bool
     */
    public static function set($file, $content)
    {
        if (@file_put_contents($file, json_encode($content)) !== FALSE) return TRUE;
        return FALSE;
    }

    /**
     * CURL获取图片
     * @param string $url
     * @return mixed
     */
    protected static function curlGet($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array());
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    /**
     * 获取Pixiv图片
     * @param array $image 缩略图完整url
     * @param array $url   作品详情页url
     * @return bool
     */
    public static function getPixivImages(&$image, &$url)
    {
        /**
         * 从缓存文件获取。这样就不必总是去P站获取排行榜了
         */
        if(Conf::$download){
            $source = self::get('source.json');
            if(!empty($source['date']) && $source['date'] == date('Y-m-d') && !empty($source['image']) && !empty($source['url'])){
                $image = $source['image'];
                $url = $source['url'];
                return TRUE;
            }
        }

        $html = self::curlGet('http://www.pixiv.net/ranking.php?mode=daily&content=illust');

        // 匹配缩略图url
        preg_match_all('|http://i\d\.pixiv\.net/c/240x480/img-master/img/\d{4}/\d{2}/\d{2}/\d{2}/\d{2}/\d{2}/(.*?\.\w{3})|', $html, $image);
        // 匹配链接
        preg_match_all('|member_illust.php\?mode=medium&amp;illust_id=\d+&amp;ref=rn-b-\d+-title-\d&amp;uarea=daily|', $html, $url);

        // 如果获取图片或url失败
        if (empty($image[0]) || empty($url[0])) return FALSE;

        /**
         * 缓存起来。这样就不必总是去P站获取排行榜了
         */
        if(Conf::$download){
            $data = array(
                'date'  => date('Y-m-d'),
                'image' => $image,
                'url'   => $url,
            );
            self::set('source.json', $data);
        }

        return TRUE;
    }

    /**
     * 删除过期的（即今天之前的）缓存缩略图
     */
    public static function clearOverdue()
    {
        if(Conf::$download && Conf::$clear_overdue){
            $time = strtotime(date('Ymd')); // 获取今日0点的时间戳，早于此时间戳的文件都得死
            if($dh = opendir(Conf::$image_path)){
                while(($file = readdir($dh)) !== FALSE){
                    if($file == '.' || $file == '..') continue;
                    $file = Conf::$image_path.$file;
                    if(filemtime($file) < $time) @unlink($file);
                }
            }
        }
    }

    /**
     * 检查是否所有缓存缩略图都有效
     */
    public static function checkImage()
    {
        $i = 0;
        if(Conf::$download && Conf::$limit){
            if($dh = opendir(Conf::$image_path)){
                while(($file = readdir($dh)) !== FALSE){
                    if($file == '.' || $file == '..') continue;

                    $file = Conf::$image_path.$file;
                    if(@getimagesize($file)){
                        $i++;
                    } else {
                        @unlink($file);
                    }
                }
            }
        }
        // 因为缩略图是先下载、再执行本函数的，所以会出现实际缓存数量大于limit的情况
        // 如果设置了不清除缓存，也会出现这种情况，所以要用 >=
        return $i >= Conf::$limit;
    }

    /**
     * 缓存缩略图
     * @param array $images 缩略图完整url数组
     * @return bool
     */
    public static function download(&$images)
    {
        // 如果设置了对外服务并且图片缓存的话，则强制缓存50张
        // 避免服务方设置了limit=10而用户请求limit=50时的问题
        if(Conf::$service && Conf::$download) Conf::$limit = 50;

        if(Conf::$download && Conf::$limit){
            foreach ($images[0] as $k=>$v){
                if(Conf::$service == FALSE && $k >= Conf::$limit) break;

                /**
                 * 每日排行榜中可能会出现昨天的作品
                 * 因此如果文件已存在并且为有效图片则修改一下“文件修改时间”
                 * 免得一会儿被clearOverdue干掉
                 * 文件不存在则下载
                 */
                $file = Conf::$image_path.$images[1][$k];
                if(file_exists($file) && @getimagesize($file) !== FALSE){
                    touch($file);
                } else {
                    $data = self::curlGet($v);
                    @file_put_contents($file, $data);
                }

                $images[0][$k] = Conf::$image_url . $images[1][$k];
            }
        }
        return TRUE;
    }

    /**
     * 执行下载线程
     */
    public static function downloadThread()
    {
        $ch = curl_init(Conf::$url.'download.php');
        curl_setopt($ch, CURLOPT_TIMEOUT, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_exec($ch);
        curl_close($ch);
    }
}