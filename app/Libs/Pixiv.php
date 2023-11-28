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

        $params = [
            'mode' => 'daily',
            'p' => $page,
            'format' => 'json',
        ];
        if (Config::$ranking_type) {
            $params['content'] = Config::$ranking_type;
        }

        $response = Curl::get('https://www.pixiv.net/ranking.php?' . http_build_query($params), [
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
     * 下载 Pixiv 缩略图。成功返回临时文件名
     * @param string $url
     * @return string 临时文件名
     */
    public static function downloadImage($url)
    {
        // 如果 local storage 已经存有这张图（每日榜上的图片是可能存在重复的），就不再重新下载了
        $image = Storage::getImage(pathinfo($url, PATHINFO_BASENAME));
        $shouldCheckComplete = !$image;
        if ($image === false) {
            $image = Curl::get($url, [
                CURLOPT_HTTPHEADER => [
                    'Referer: https://www.pixiv.net/ranking.php?mode=daily',
                ],
            ]);

            Log::write('下载到数据包大小： ' . strlen($image) . ' 字节');
        }

        if ($image) {
            $file = explode('/', $url);
            $file = array_pop($file);
            $file = sys_get_temp_dir() . '/' . Str::random(16) . $file;

            $bytes = file_put_contents($file, $image);
            Log::write("写入文件 {$file} 大小：{$bytes} 字节");

            // 检查文件是否下载完整
            if ($shouldCheckComplete) {
                $response = Curl::get($url, [
                    CURLOPT_NOBODY => true,
                    CURLOPT_HEADER => true,
                    CURLOPT_HTTPHEADER => [
                        'Referer: https://www.pixiv.net/ranking.php?mode=daily',
                    ],
                ]);
                $contentLength = Response::getContentLength($response);
                if ($bytes != $contentLength) {
                    Log::write("写入的文件大小与目标 content-length: {$contentLength} 不符");
                    return false;
                }
            }

            return $bytes > 0 ? $file : false;
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

    /**
     * 获取基于 pixiv.cat 提供的代理服务的图片 url
     * 可以直接展示在页面上，突破 pixiv 的反盗链
     * @param $url
     * @return array|string|string[]
     */
    public static function getProxyUrl($url)
    {
        return str_replace('i.pximg.net', 'i.pixiv.re', $url);
    }
}