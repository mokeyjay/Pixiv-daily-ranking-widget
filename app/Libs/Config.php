<?php

namespace app\Libs;

/**
 * 配置类
 * Class Config
 * @package app\Libs
 */
class Config
{
    // 此处属性对应 config.php 内的配置项
    public static $url = '';
    public static $background_color = 'transparent';
    public static $limit = 50;
    public static $service = true;
    public static $log_level = [];
    public static $proxy = '';
    public static $clear_overdue = true;
    public static $compress = true;
    public static $image_hosting = ['local'];
    public static $image_hosting_extend = [];

    /**
     * 初始化配置
     */
    public static function init()
    {
        // 读取配置项
        $config = require BASE_PATH . 'config.php';
        foreach ($config as $key => $value) {
            self::$$key = $value;
        }

        // 获取本项目url
        if (self::$url == '' && !IS_CLI) {
            self::$url = Tools::getCurrentURL();
        }

        // 是否对外提供服务，是则获取url参数
        if (self::$service) {
            if (isset($_GET['color'])) {
                self::$background_color = '#' . $_GET['color'];
            }
            if (isset($_GET['limit'])) {
                self::$limit = (int)$_GET['limit'];
            }
        }

        Config::$image_hosting = (array)Config::$image_hosting;

        try {
            if (!is_writable(STORAGE_PATH)) {
                throw new \Exception(STORAGE_PATH . ' 目录无法写入');
            }

            if (self::$limit < 1) {
                throw new \Exception('limit 配置项不得小于1');
            }

            if (IS_CLI && self::$url == '' && in_array('local', self::$image_hosting)) {
                throw new \Exception('在cli模式下使用local本地图床时，必须配置url项，否则可能会生成错误的缩略图url');
            }

        } catch (\Exception $e) {
            Tools::log($e->getMessage(), 'ERROR');
            if (!IS_CLI) {
                echo '错误：' . $e->getMessage();
            }
            die;
        }
    }
}