<?php

namespace app\ImageHosting;

use app\Factory;

/**
 * 抽象 图床类
 * Class ImageHosting
 * @package app\ImageHosting
 */
abstract class ImageHosting extends Factory
{
    /**
     * @param string $name
     * @param array  $config
     * @return self
     */
    public static function make($name, array $config = [])
    {
        $name = '\\app\\ImageHosting\\' . ucfirst(strtolower($name));
        return parent::make($name, $config);
    }

    /**
     * 上传图片。成功返回url，失败返回false
     * @param string $path 图片路径
     * @return string|false
     */
    abstract public function upload($path);
}