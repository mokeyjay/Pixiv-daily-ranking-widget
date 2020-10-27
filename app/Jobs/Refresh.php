<?php

namespace app\Jobs;

use app\ImageHosting\ImageHosting;
use app\Libs\Config;
use app\Libs\Lock;
use app\Libs\Pixiv;
use app\Libs\Storage;
use app\Libs\Tools;

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

            if(!$this->needRefresh($ranking, $pixivJson)){
                Tools::log('排行榜尚未更新，半小时后再试');
                Lock::forceCreate('refresh', 1800);
                return true;
            }

            $images = Pixiv::getImages();
            if($images === false) {
                throw new \Exception('【致命错误】无法获取Pixiv排行榜图片列表');
            }

            $pixivJson = [
                'image' => [],
                'url'   => [],
            ];

            $enableCompress = Config::$compress && function_exists('imagecreatefromjpeg');

            $imageHostingInstances = [];
            foreach (Config::$image_hosting as $ihName) {
                $imageHostingInstances[] = ImageHosting::make($ihName);
            }

            // 开始获取图片
            foreach ($images['image'] as $i => $imageUrl) {
                // 缓存数量限制
                if ($i >= Config::$limit) {
                    break;
                }
                // 最多尝试下载3次
                for ($ii = 1; $ii <= 3; $ii++) {
                    $tmpfile = Pixiv::downloadImage($imageUrl);
                    if ($tmpfile) {
                        break;
                    } else {
                        Tools::log("图片 {$imageUrl} 下载失败，重试第 {$ii} 次");
                        sleep(3);
                    }
                }
                if (!$tmpfile) {
                    throw new \Exception("图片 {$imageUrl} 下载失败");
                }
                // 压缩图片
                if ($enableCompress) {
                    $image = imagecreatefromjpeg($tmpfile);
                    if ($image) {
                        imagejpeg($image, $tmpfile, 95);
                        imagedestroy($image);
                        unset($image);
                    }
                }
                // 上传到图床
                foreach ($imageHostingInstances as $imageHosting) {
                    $url = $imageHosting->upload($tmpfile);
                    if ($url != false) {
                        Storage::deleteFile($tmpfile);
                        break;
                    }
                }

                $pixivJson['image'][] = $url ?: $images['image'][$i]; // 如上传失败则使用原图url（虽然原图url也显示不出来）
                $pixivJson['url'][] = $images['url'][$i];
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
                // 超过10次（5小时）都无法获取到pixiv排行榜
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