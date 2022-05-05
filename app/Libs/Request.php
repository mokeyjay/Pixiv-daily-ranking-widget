<?php

namespace App\Libs;

/**
 * 请求类
 */
class Request
{
    /**
     * 获取当前请求的协议
     * @return string
     */
    public static function getScheme()
    {
        if (isset($_SERVER['REQUEST_SCHEME'])) {
            return $_SERVER['REQUEST_SCHEME'];
        }

        if (
            (!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off') ||
            (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') ||
            (!empty($_SERVER['HTTP_FRONT_END_HTTPS']) && strtolower($_SERVER['HTTP_FRONT_END_HTTPS']) !== 'off')
        ) {
            return 'https';
        }

        return 'http';
    }

    /**
     * 获取当前 url （以 / 结尾。不含 query 参数及文件名）
     * @return string
     */
    public static function getCurrentUrl()
    {
        $url = self::getScheme() . '://' . $_SERVER['HTTP_HOST'];
        if (!in_array($_SERVER['SERVER_PORT'], ['80', '443'])) {
            $url .= ':' . $_SERVER['SERVER_PORT'];
        }

        $scriptName = explode('/', $_SERVER['SCRIPT_NAME']);
        $scriptName = array_pop($scriptName);

        $path = explode($scriptName, $_SERVER['REQUEST_URI']);
        $path = array_shift($path);

        $url .= rtrim($path, '/') . '/';

        return $url;
    }

    /**
     * 执行刷新线程
     */
    public static function execRefreshThread()
    {
        if (Config::$disable_web_job) {
            return;
        }

        Config::$proxy = null;
        Curl::get(Config::$url . 'index.php?job=refresh', [
            CURLOPT_TIMEOUT => 1,
        ]);
    }
}