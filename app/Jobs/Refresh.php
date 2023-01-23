<?php

namespace app\Jobs;

use app\ImageHosting\ImageHosting;
use app\Libs\Config;
use app\Libs\Lock;
use app\Libs\Pixiv;
use app\Libs\Storage;
use app\Libs\Log;

/**
 * 刷新任务
 *
 * 以下 2 种情况需要刷新排行榜：
 * 1、昨天的排行榜已经出来了
 * 2、昨天的排行榜还没出，但没有 pixiv.json 文件。这可能是第一次新安装，当然需要刷新
 *
 * Class Refresh
 * @package app\Jobs
 */
class Refresh extends Job
{
    public function run()
    {
        try {
            $pixivJson = Storage::getJson('pixiv');
            $ranking = Pixiv::getRanking();

            if ($ranking === false) {
                return false;
            }

            if(!$this->needRefresh($ranking, $pixivJson)){
                Log::write('排行榜尚未更新，半小时后再试');
                Lock::forceCreate('refresh', 1800);

                return true;
            }

            $images = Pixiv::getImages();
            if($images === false) {
                throw new \Exception('【致命错误】无法获取Pixiv排行榜图片列表');
            }

            $enableCompress = Config::$compress && function_exists('imagecreatefromjpeg');

            $imageHostingInstances = [];
            foreach (Config::$image_hosting as $ihName) {
                $imageHostingInstances[] = ImageHosting::make($ihName);
            }

            $proxy = Config::$proxy;

            // 开始获取图片
            $pixivJson = [];
            foreach ($images['data'] as $i => $data) {
                // 缓存数量限制
                if ($i >= Config::$limit) {
                    break;
                }

                Log::write("开始获取第 " . ($i + 1) . " 张图：{$data['url']}");

                // 最多尝试下载 3 次
                Config::$proxy = $proxy;
                for ($ii = 1; $ii <= 3; $ii++) {
                    $tmpfile = Pixiv::downloadImage($data['url']);
                    if ($tmpfile && getimagesize($tmpfile)) {
                        break;
                    } else {
                        Log::write("图片 {$data['url']} 下载失败，重试第 {$ii} 次");
                        sleep(mt_rand(3, 30));
                    }
                }
                if (!$tmpfile) {
                    throw new \Exception("图片 {$data['url']} 下载失败");
                }

                // 压缩图片
                if ($enableCompress) {
                    $image = imagecreatefromjpeg($tmpfile);
                    if ($image) {
                        imagejpeg($image, $tmpfile, 95);
                        $bytes = filesize($tmpfile);
                        Log::write('压缩后图片大小： ' . $bytes . ' 字节');
                        imagedestroy($image);
                        unset($image);
                    }

                    if ($bytes < 1000) {
                        throw new \Exception("图片 {$data['url']} 下载失败");
                    }
                }

                // 上传到图床
                Config::$proxy = null; // 上传过程中禁用代理
                foreach ($imageHostingInstances as $imageHosting) {
                    $url = $imageHosting->upload($tmpfile);
                    if ($url != false) {
                        Storage::deleteFile($tmpfile);
                        break;
                    }
                }

                $url = $url ?: Pixiv::getProxyUrl($data['url']); // 如上传失败则使用反代 url
                $data['url'] = $url;

                $pixivJson['data'][] = $data;
                $pixivJson['image'][] = $url;
                $pixivJson['url'][] = "artworks/{$data['id']}";
            }

            $pixivJson['date'] = $images['date'];
            Storage::saveJson('pixiv', $pixivJson);
            Lock::remove('refresh');

            Config::$clear_overdue && Storage::clearOverdueImages();

            return true;

        } catch (\Exception $e) {

            // 是否超过最大重试次数
            $refreshCount = (int)Storage::get('refreshCount');
            if ($refreshCount > 10) {
                // 超过 10 次（5小时）都无法获取到 pixiv 排行榜
                // 直接锁定一整天，明天再试，降低无意义的资源损耗
                $expire = mktime(23, 59, 59) - time();
                Lock::forceCreate('refresh', $expire);
                Storage::remove('refreshCount');
            } else {
                // 半小时后再试
                Lock::forceCreate('refresh', 1800);
                Storage::save('refreshCount', $refreshCount + 1);
            }

            $this->errorMsg = $e->getMessage();
            return false;
        }
    }

    /**
     * 是否需要刷新数据
     * @param array $ranking pixiv 接口返回的排行榜数据
     * @param array $pixivJson pixiv.json 的内容
     * @return bool
     * @throws \Exception
     */
    private function needRefresh($ranking, $pixivJson)
    {
        if (!isset($pixivJson['date'])) {
            return true;
        }

        // $ranking['date'] 的格式为 20200310
        if ($ranking && isset($ranking['date']) && preg_match('|^\d{8}$|', $ranking['date'])) {

            return $pixivJson['date'] != date('Y-m-d', strtotime($ranking['date']));
        }

        throw new \Exception('排行榜日期数据异常！数据：' . json_encode($ranking));
    }
}