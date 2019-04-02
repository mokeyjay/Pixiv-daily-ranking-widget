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
     * 保存文件内容
     * @param string       $file
     * @param string|array $content
     * @return bool
     */
    public static function save($file, $content)
    {
        $file = STORAGE_PATH . 'app/' . $file;
        return file_put_contents($file, json_encode($content)) !== false;
    }

    /**
     * 获取文件内容
     * @param string $file
     * @return array|false
     */
    public static function get($file)
    {
        $file = STORAGE_PATH . 'app/' . $file;
        if (is_readable($file)) {
            $file = file_get_contents($file);
            if ($file === false) {
                Tools::log('读取 ' . $file . ' 文件失败');
                return false;
            }

            return json_decode($file, true);
        }
        return false;
    }

    /**
     * 删除文件
     * @param string $file 文件名
     * @return bool
     */
    public static function remove($file)
    {
        return self::deleteFile(STORAGE_PATH . 'app/' . $file);
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
        $path = STORAGE_PATH . 'images/' . $name;
        if (file_exists($path) && getimagesize($path)) {
            return file_get_contents($path);
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
        return unlink($path);
    }
}