<?php

namespace app\Libs;

/**
 * Pixiv 类
 * Class Pixiv
 * @package app\Libs
 */
class Pixiv
{
    /**
     * 获取缓存文件。成功返回数组
     * @param string $name
     * @return array|false
     */
    public static function getJson($name)
    {
        $json = Storage::get($name . '.json');
        if ($json === false || !isset($json['date'])) {
            return false;
        }

        return $json['date'] == date('Y-m-d') ? $json : false;
    }

    /**
     * 获取Pixiv图片url列表
     * @return array|false
     */
    public static function getImageList()
    {
        $imageList = self::getJson('source');
        if ($imageList) {
            return $imageList;
        }

        $html = Curl::get('https://www.pixiv.net/ranking.php?mode=daily&content=illust');
        preg_match_all('|https://i\.pximg\.net/c/240x480/img-master/img/\d{4}/\d{2}/\d{2}/\d{2}/\d{2}/\d{2}/(.*?\.\w{3})|', $html, $image); // 匹配缩略图url
        preg_match_all('|member_illust.php\?mode=medium&amp;illust_id=\d+&amp;ref=rn-b-\d+-title-\d&amp;uarea=daily|', $html, $url); // 匹配链接

        // 如果获取图片或url失败
        if (empty($image[0]) || empty($url[0]))
            return false;

        $json = [
            'date'  => date('Y-m-d'),
            'image' => $image[0],
            'url'   => $url[0],
        ];
        Storage::save('source.json', $json);

        return $json;
    }

    /**
     * 下载Pixiv缩略图。成功返回临时文件名
     * @param string $url
     * @return string 临时文件名
     */
    public static function downloadImage($url)
    {
        $fileName = pathinfo($url, PATHINFO_BASENAME);
        // 如果 storage 里存了有，就不再重新下载了
        $image = Storage::getImage($fileName);
        if ($image == false) {
            $image = Curl::get($url, [
                CURLOPT_HTTPHEADER => [
                    'Referer: https://www.pixiv.net/ranking.php?mode=daily&content=illust',
                ],
            ]);
        }
        if ($image) {
            $file = explode('/', $url);
            $file = array_pop($file);
            $file = sys_get_temp_dir() . '/' . $file;
            return file_put_contents($file, $image) !== false ? $file : false;
        }
        return false;
    }

    /**
     * 执行刷新线程
     */
    public static function runRefreshThread()
    {
        Curl::get(Config::$url . 'index.php?job=refresh', [
            CURLOPT_TIMEOUT => 1,
        ]);
    }
}