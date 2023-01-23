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
    public static $static_cdn = 'bytedance';
    public static $static_cdn_url = [];
    public static $header_script = '';

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

        self::initStaticCdnUrl(self::$static_cdn);

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

        } catch (\Exception $e) {
            Log::write($e->getMessage(), Log::LEVEL_ERROR);
            echo '错误：' . $e->getMessage();

            die;
        }
    }

    /**
     * 初始化静态资源 CDN
     * @param $provider
     * @return void
     */
    private static function initStaticCdnUrl($provider)
    {
        $url = [
            'bootcdn' => [
                'bootstrap-css' => 'https://cdn.bootcdn.net/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css',
                'bootstrap-js' => 'https://cdn.bootcdn.net/ajax/libs/twitter-bootstrap/5.2.0/js/bootstrap.min.js',
            ],
            'baomitu' => [
                'bootstrap-css' => 'https://lib.baomitu.com/twitter-bootstrap/5.2.0/css/bootstrap.min.css',
                'bootstrap-js' => 'https://lib.baomitu.com/twitter-bootstrap/5.2.0/js/bootstrap.bundle.min.js',
            ],
            'bytedance' => [
                'bootstrap-css' => 'https://lf6-cdn-tos.bytecdntp.com/cdn/expire-1-M/bootstrap/5.1.3/css/bootstrap.min.css',
                'bootstrap-js' => 'https://lf3-cdn-tos.bytecdntp.com/cdn/expire-1-M/bootstrap/5.1.3/js/bootstrap.min.js',
            ],
            'cdnjs' => [
                'bootstrap-css' => 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.0/css/bootstrap.min.css',
                'bootstrap-js' => 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.0/js/bootstrap.min.js',
            ],
            'jsdelivr' => [
                'bootstrap-css' => 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css',
                'bootstrap-js' => 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.min.js',
            ],
        ];

        if (!isset($url[$provider])) {
            $provider = 'bytedance';
        }

        self::$static_cdn_url = $url[$provider];
    }
}