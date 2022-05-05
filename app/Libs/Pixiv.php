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
        Log::write("正在读取排行榜第 {$page} 页");
        $response = Curl::get("https://www.pixiv.net/ranking.php?mode=daily&p={$page}&format=json", [
            CURLOPT_HTTPHEADER => [
                'Referer: https://www.pixiv.net/ranking.php?mode=daily',
            ],
        ]);
        $json = json_decode($response, true);
        if (!isset($json['contents'])) {
            Log::write('获取排行榜数据失败！接口返回值：' . $response, Log::LEVEL_ERROR);
            return false;
        }

        return $json;
    }

    /**
     * 获取图片url列表
     * @return array|false
     * @throws \Exception
     */
    public static function getImages()
    {
        $source = Storage::getJson('source');
        if (is_array($source) && self::checkDate($source)) {
            return $source;
        }

        $picNum = 0;
        $sourceJson = [];
        for ($page = 1; $page <= 10; $page++) {

            $json = self::getRanking($page);
            if($json === false){
                return false;
            }

            foreach ($json['contents'] as $item) {
                $sourceJson['data'][] = [
                    'id' => $item['illust_id'],
                    'url' => $item['url'],
                    'title' => $item['title'],
                    'tags' => $item['tags'],
                    'width' => $item['width'],
                    'height' => $item['height'],
                    'page_count' => $item['illust_page_count'],
                    'rank' => $item['rank'],
                    'yesterday_rank' => $item['yes_rank'],
                    'user_id' => $item['user_id'],
                    'user_name' => $item['user_name'],
                    'uploaded_at' => $item['illust_upload_timestamp'],
                ];
                // image 和 url 是为了兼容 5.x 之前的旧版本
                $sourceJson['image'][] = $item['url'];
                $sourceJson['url'][] = "artworks/{$item['illust_id']}";
                $picNum++;

                if ($picNum >= Config::$limit) {
                    break 2;
                }
            }
        }

        $sourceJson['date'] = date('Y-m-d', strtotime($json['date']));
        Storage::saveJson('source', $sourceJson);

        return $sourceJson;
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

    /**
     * 检查传入数组的 date 值是否有效（即大于等于昨天）。返回 true 为未过期
     * @param array $data
     * @return bool
     */
    public static function checkDate(array $data)
    {
        if(isset($data['date'])){
            $yesterday = date('Y-m-d', strtotime('-1 day'));

            return $data['date'] >= $yesterday;
        }

        return false;
    }
}