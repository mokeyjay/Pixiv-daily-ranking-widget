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
     * 调用官方ajax接口获取排行榜数据
     * @param int $page 页码，最多10页
     * @return mixed
     * @throws \Exception
     */
    public static function getRanking($page = 1)
    {
        $response = Curl::get("https://www.pixiv.net/ranking.php?mode=daily&p={$page}&format=json", [
            CURLOPT_HTTPHEADER => [
                'Referer: https://www.pixiv.net/ranking.php?mode=daily',
            ],
        ]);
        $json = json_decode($response, true);
        if (!isset($json['contents'])) {
            Tools::log('获取排行榜数据失败！接口返回值：' . $response, Tools::LOG_LEVEL_ERROR);
            return false;
        }

        return $json;
    }

    /**
     * 检查排行榜是否已更新
     * @return bool
     * @throws \Exception
     */
    public static function checkRankingUpdate()
    {
        $json = self::getRanking();

        if ($json && isset($json['date']) && preg_match('|^\d{8}$|', $json['date'])) {
            return date('Y-m-d', strtotime($json['date'])) === date('Y-m-d');
        }

        throw new \Exception('检查排行榜更新失败！接口返回值：' . json_encode($json));
    }

    /**
     * 获取图片url列表
     * @return array|false
     * @throws \Exception
     */
    public static function getImages()
    {
        $source = Storage::getJson('source', true);
        if (is_array($source)) {
            return $source;
        }

        // 兼容旧格式
        $source = [
            'image' => [],
            'url'   => [],
        ];

        $picNum = 0;
        for ($page = 1; $page <= 10; $page++) {

            $json = self::getRanking($page);
            if($json === false){
                return false;
            }

            foreach ($json['contents'] as $item) {
                $source['image'][] = $item['url'];
                $source['url'][] = "artworks/{$item['illust_id']}";
                $picNum++;

                if ($picNum >= Config::$limit) {
                    break 2;
                }
            }
        }

        $source['date'] = date('Y-m-d', strtotime($json['date']));
        Storage::saveJson('source', $source);

        return $source;
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
                    'Referer: https://www.pixiv.net/ranking.php?mode=daily',
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
}