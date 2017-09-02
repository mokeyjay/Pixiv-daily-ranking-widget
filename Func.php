<?php
/**
 * 项目：Pixiv每日排行榜Top50小部件
 * 作者：超能小紫(mokeyjay)
 * 博客：https://www.mokeyjay.com
 * 源码：https://github.com/mokeyjay/Pixiv-daily-top50-widget
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
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
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
        if (Conf::$download){
            $source = self::get('source.json');
            if ( !empty($source['date']) && $source['date'] == date('Y-m-d') && !empty($source['image']) && !empty($source['url'])){
                $image = $source['image'];
                $url = $source['url'];
                return TRUE;
            }
        }

        $html = self::curlGet('http://www.pixiv.net/ranking.php?mode=daily&content=illust');

        // 匹配缩略图url
        preg_match_all('|https://i\.pximg\.net/c/240x480/img-master/img/\d{4}/\d{2}/\d{2}/\d{2}/\d{2}/\d{2}/(.*?\.\w{3})|', $html, $image);
        // 匹配链接
        preg_match_all('|member_illust.php\?mode=medium&amp;illust_id=\d+&amp;ref=rn-b-\d+-title-\d&amp;uarea=daily|', $html, $url);

        // 如果获取图片或url失败
        if (empty($image[0]) || empty($url[0])) return FALSE;

        /**
         * 缓存起来。这样就不必总是去P站获取排行榜了
         */
        if (Conf::$download){
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
        if (Conf::$download && Conf::$clear_overdue){
            $time = strtotime(date('Ymd')); // 获取今日0点的时间戳，早于此时间戳的文件都得死
            if ($dh = opendir(Conf::$image_path)){
                while (($file = readdir($dh)) !== FALSE){
                    if ($file == '.' || $file == '..') continue;
                    $file = Conf::$image_path . $file;
                    if (filemtime($file) < $time) @unlink($file);
                }
            }
        }
    }

    /**
     * 检查是否所有缓存缩略图都有效
     * @return bool
     */
    public static function checkImage()
    {
        if (Conf::$download && Conf::$limit){
            if ($dh = opendir(Conf::$image_path)){
                while (($file = readdir($dh)) !== FALSE){
                    if ($file == '.' || $file == '..') continue;

                    $file = Conf::$image_path . $file;
                    if (@getimagesize($file) === FALSE){
                        @unlink($file);
                        return FALSE;
                    }
                }
            } else {
                return FALSE;
            }
        }
        return TRUE;
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
        if (Conf::$service && Conf::$download) Conf::$limit = 50;

        if (Conf::$download && Conf::$limit){
            // 创建/清空图床上传日志文件
            if(Conf::$enable_smms) @file_put_contents('log', '');

            foreach ($images[0] as $k => $v){
                if (Conf::$service == FALSE && $k >= Conf::$limit) break;

                /**
                 * 下载P站缩略图
                 * 并根据配置判断是否使用sm.ms图床
                 */
                $file = Conf::$image_path . $images[1][$k];
                if(file_exists($file)){ // 已存在的图片不再下载
                    $data = file_get_contents($file);
                } else {
                    $data = self::curlGet($v);
                }
                $data = @file_put_contents($file, $data);

                // 压缩图片
                if(Conf::$enable_comporess){
                    $image = @imagecreatefromjpeg($file);
                    if($image){
                        imagejpeg($image, $file, 95);
                        imagedestroy($image);
                    }
                }

                if ($data !== FALSE && (Conf::$enable_smms || Conf::$enable_tietuku)){
                    // 上传到图床
                    for ($i = 0; $i < 3; $i++){ // 最多尝试3次
                        if ($i > 0) sleep(3); // 等待3秒重试

                        if(Conf::$enable_smms){
                            $url = self::smmsUpload($file);
                        } else {
                            $url = self::tietukuUpload($v);
                        }
                        if ($url !== FALSE) break;
                    }

                    if ($url !== FALSE){
                        $images[0][$k] = $url;
                        continue;
                    }
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
        $ch = curl_init(Conf::$url . 'download.php');
        curl_setopt($ch, CURLOPT_TIMEOUT, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_exec($ch);
        curl_close($ch);
    }

    /**
     * 上传到sm.ms图床
     * @param string $file
     * @return string 返回图床图片url。失败返回false
     */
    public static function smmsUpload($file)
    {
        $ch = curl_init('https://sm.ms/api/upload');
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/59.0.3071.115 Safari/537.36');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        if (class_exists('CURLFile')){
            curl_setopt($ch, CURLOPT_POSTFIELDS, ['smfile' => new CURLFile(realpath($file))]);
        } else {
            curl_setopt($ch, CURLOPT_POSTFIELDS, ['smfile' => '@' . realpath($file)]);
        }
        $result = curl_exec($ch);
        self::writeLog($result);
        $result = json_decode($result, TRUE);
        curl_close($ch);

        return (isset($result['code']) && $result['code'] == 'success') ? $result['data']['url'] : FALSE;
    }

    /**
     * 上传到贴图库图床
     * @param string $fileurl
     * @return string 返回图床图片url。失败返回false
     */
    public static function tietukuUpload($fileurl)
    {
        $ch = curl_init('http://up.imgapi.com/');
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/59.0.3071.115 Safari/537.36');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, [
            'Token' => Conf::$tietuku_token,
            'fileurl' => $fileurl
        ]);
        $result = curl_exec($ch);
        self::writeLog($result);
        $result = json_decode($result, TRUE);
        curl_close($ch);

        return !empty($result['linkurl']) ? $result['linkurl'] : FALSE;
    }

    /**
     * 写日志
     * @param string $data
     * @return bool|int
     */
    public static function writeLog($data)
    {
        $handle = fopen('log', 'a');
        if($handle){
            $result = fwrite($handle, date('H:i:s') . ' --> ' . $data . "\n");
            fclose($handle);
            return $result;
        }

        return FALSE;
    }
}