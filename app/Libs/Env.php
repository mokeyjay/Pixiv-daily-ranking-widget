<?php

namespace app\Libs;

/**
 * 环境变量
 * Class Env
 * @package app\Libs
 */
class Env
{
    /**
     * 从环境变量中读取字符串
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public static function getStr($name, $default = '')
    {
        $data = getenv($name);

        return $data === false ? $default : $data;
    }

    /**
     * 检查布尔环境变量是否存在。若不存在，返回默认值
     * @param string $name
     * @param mixed $default
     * @return bool
     */
    public static function getBool($name, $default = false)
    {
        return strtolower(self::getStr($name, $default)) === 'true';
    }

    /**
     * 检查数组环境变量是否存在。若不存在，返回默认值
     * 数组的值为 ',' 分割的字符串
     * @param string $name
     * @param array $default
     * @return array|false
     */
    public static function getArray($name, $default = [])
    {
        $data = self::getStr($name, $default);

        return is_array($data) ? $data : explode(',', $data);
    }
}