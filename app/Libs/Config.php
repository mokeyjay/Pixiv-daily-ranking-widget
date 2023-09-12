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
    public static $disable_web_job = false;
    public static $header_script = '';
    public static $ranking_type = '';

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

        // 获取本项目 url
        if (self::$url == '' && !IS_CLI) {
            self::$url = Request::getCurrentUrl();
        }

        // 是否对外提供服务，是则获取 url 参数
        if (self::$service) {
            if (isset($_GET['color'])) {
                self::$background_color = '#' . $_GET['color'];
            }
            if (isset($_GET['limit'])) {
                self::$limit = (int)$_GET['limit'];
            }
        }

        try {
            if (!is_writable(STORAGE_PATH)) {
                throw new \Exception(STORAGE_PATH . ' 目录无法写入');
            }

            if (!is_array(Config::$image_hosting) || count(Config::$image_hosting) < 1) {
                throw new \Exception('image_hosting 配置项至少要有一个值');
            }

            if (self::$limit < 1) {
                throw new \Exception('limit 配置项不得小于1');
            }

            if (IS_CLI && self::$url == '' && in_array('local', self::$image_hosting)) {
                throw new \Exception('在 cli 模式下使用 local 本地图床时，必须配置 url 项，否则可能会生成错误的缩略图 url');
            }

            if (!in_array(Config::$ranking_type, ['', 'illust', 'manga'])) {
                throw new \Exception('ranking_type 配置项必须为空、illust 或 manga');
            }

        } catch (\Exception $e) {
            Log::write($e->getMessage(), Log::LEVEL_ERROR);
            echo '错误：' . $e->getMessage();

            die;
        }
    }
}