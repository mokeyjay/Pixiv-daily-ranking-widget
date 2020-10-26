<?php

namespace app\Libs;

/**
 * 存储类
 * Class Storage
 * @package app\Libs
 */
class Storage
{
    /**
     * 保存到文件
     * @param string       $file
     * @param string|array $content
     * @return bool
     */
    public static function save($file, $content)
    {
        $file = STORAGE_PATH . $file;
        return file_put_contents($file, $content) !== false;
    }

    /**
     * 获取文件内容
     * @param string $file
     * @return mixed|false
     */
    public static function get($file)
    {
        $file = STORAGE_PATH . $file;
        if (is_readable($file) === false) {
            return false;
        }
        $content = @file_get_contents($file);
        if ($content === false) {
            Tools::log("读取 {$file} 文件失败");
            return false;
        }

        return $content;
    }

    /**
     * 删除文件
     * @param string $file 文件名
     * @return bool
     */
    public static function remove($file)
    {
        return self::deleteFile(STORAGE_PATH . $file);
    }

    /**
     * 清除过期地图片
     */
    public static function clearOverdueImages()
    {
        $deleteNum = 0;
        $time = strtotime(date('Ymd')); // 获取今日0点的时间戳，早于此时间戳的文件都得死
        if ($dh = opendir(STORAGE_PATH . 'images/')) {
            while (($file = readdir($dh)) !== false) {
                if (in_array($file, ['.', '..', '.gitignore'])) {
                    continue;
                }
                $file = STORAGE_PATH . 'images/' . $file;
                if (filemtime($file) < $time) {
                    $deleteNum++;
                    self::deleteFile($file);
                }
            }
        }
        Tools::log("共计清除过期图片 {$deleteNum} 张");
    }

    /**
     * 获取图片内容。文件不存在或无效时返回false
     * @param string $name
     * @return mixed|false
     */
    public static function getImage($name)
    {
        $path = 'images/' . $name;
        if (file_exists(STORAGE_PATH . $path) && getimagesize(STORAGE_PATH . $path)) {
            return self::get($path);
        }
        return false;
    }

    /**
     * 删除文件
     * @param string $path
     * @return bool
     */
    public static function deleteFile($path)
    {
        return @unlink($path);
    }

    /**
     * 保存数组到json文件
     * @param string $file 文件名。无需后缀名
     * @param array  $data
     * @return bool
     */
    public static function saveJson($file, array $data)
    {
        return self::save("app/{$file}.json", json_encode($data));
    }

    /**
     * 获取json数组内容
     * @param string $file 文件名。无需后缀名
     * @return mixed|false
     */
    public static function getJson($file)
    {
        $content = self::get("app/{$file}.json");
        $content = json_decode($content, true);
        if (!is_array($content)) {
            return false;
        }

        return $content;
    }
}