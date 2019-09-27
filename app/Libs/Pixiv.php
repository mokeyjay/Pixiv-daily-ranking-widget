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
     * 获取Pixiv图片url列表
     * @return array|false
     */
    public static function getImageList()
    {
        $imageList = Storage::getJson('source', true);
        if (is_array($imageList)) {
            return $imageList;
        }

        $html = Curl::get('https://www.pixiv.net/ranking.php?mode=daily&content=illust');
        preg_match_all('|https://i\.pximg\.net/c/240x480/img-master/img/\d{4}/\d{2}/\d{2}/\d{2}/\d{2}/\d{2}/.*?\.\w{3}|', $html, $image); // 匹配缩略图url
        preg_match_all('|<a href="/(artworks/\d+)"class="title"|', $html, $url); // 匹配链接

        // 如果获取图片或url失败
        if (empty($image[0]) || empty($url[1]))
            return false;

        $json = [
            'image' => $image[0],
            'url'   => $url[1],
        ];
        Storage::saveJson('source', $json);

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