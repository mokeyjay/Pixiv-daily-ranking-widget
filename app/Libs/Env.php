<?php

namespace app\Libs;

/**
 * 环境变量
 * Class Env
 * @package app\Libs
 */
class Env
{
    private static $env = [];

    /**
     * 从指定环境变量文件读取
     * 避免被 crontab 触发时无法读到环境变量的问题
     * @param $file
     * @return void
     * @throws \Exception
     */
    public static function init($file = '.env')
    {
        if (!is_readable(BASE_PATH . $file)) {
            throw new \Exception('无法读取 .env 环境变量文件');
        }

        self::$env = parse_ini_file(BASE_PATH . $file);

        if (self::$env === false) {
            throw new \Exception('无法解析 .env 环境变量文件');
        }
    }

    /**
     * 从环境变量中读取字符串
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public static function getStr($name, $default = '')
    {
        $data = self::$env[$name] ?: false;

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