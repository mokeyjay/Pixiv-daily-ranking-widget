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
     * @param $name
     * @param $default
     * @return string
     */
    public static function getStrEnv($name, $default='')
    {
        return getenv($name) ?: $default;
    }

    /**
     * 检查布尔环境变量是否存在, 若不存在, 返回默认值
     * @param $name
     * @param $default
     * @return bool|mixed
     */
    public static function getBoolEnv($name, $default=false)
    {
        $data = getenv($name);
        if($data === false) return $default;
        if(strtolower(strval($data)) === 'false') return false;
        return true;
    }

    /**
     * 检查数组环境变量是否存在, 若不存在, 返回默认值
     * 数组的值为 ',' 分割的字符串
     * @param $name
     * @param $default
     * @return array|false|mixed|string[]
     */
    public static function getArrayEnv($name, $default=[])
    {
        $data = getenv($name);
        if($data === false) return $default;
        return explode(',', $data);
    }
}