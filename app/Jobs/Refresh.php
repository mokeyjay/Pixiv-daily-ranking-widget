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
 * Class Refresh
 * @package app\Jobs
 */
class Refresh extends Job
{
    public function run()
    {
        if (!Lock::create('refresh', 1800)) {
            Tools::log('锁创建失败，可能是刷新操作执行中');
            $this->errorMsg = '锁创建失败，可能是刷新操作执行中';
            return false;
        }

        try {
            $sourceJson = Pixiv::getImageList();
            if (!$sourceJson) {
                throw new \Exception('【致命错误】无法获取Pixiv排行榜图片列表');
            }

            if (Config::$service) {
                // 如果设置了对外服务并且图片缓存的话，则强制缓存50张
                // 避免服务方设置了 limit=10 而用户请求 limit=50 时的问题
                Config::$limit = 50;
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
            foreach ($sourceJson['image'] as $i => $imageUrl) {
                // 缓存数量限制
                if (Config::$service === false && Config::$limit <= $i) {
                    break;
                }
                // 最多尝试下载3次
                for ($ii = 0; $ii < 3; $ii++) {
                    $tmpfile = Pixiv::downloadImage($imageUrl);
                    if ($tmpfile) {
                        break;
                    } else {
                        Tools::log("图片 {$imageUrl} 下载失败，重试第{$ii}次");
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

                $pixivJson['image'][] = $url ?: $sourceJson['image'][$i]; // 如上传失败则使用原图url（虽然原图url也显示不出来）
                $pixivJson['url'][] = $sourceJson['url'][$i];
            }
            Storage::saveJson('pixiv', $pixivJson);
            Lock::remove('refresh');

            Config::$clear_overdue && Storage::clearOverdueImages();
            return true;

        } catch (\Exception $e) {
            Lock::remove('refresh');
            Tools::log($e->getMessage(), 'ERROR');
            $this->errorMsg = $e->getMessage();
            return false;
        }
    }
}