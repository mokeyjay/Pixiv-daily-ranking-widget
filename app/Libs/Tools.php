<?php

namespace app\Libs;

/**
 * 工具类
 * Class Tools
 * @package app\Libs
 */
class Tools
{
    const LOG_LEVEL_ERROR = 'ERROR';

    /**
     * 写日志
     * @param string|array $message
     * @param string       $level
     * @return bool
     */
    public static function log($message, $level = 'DEBUG')
    {
        $level = strtoupper($level);
        if (is_array(Config::$log_level) && in_array($level, Config::$log_level)) {
            $file = STORAGE_PATH . 'logs/' . date('Ymd') . (IS_CLI ? '-cli' : '') . '.log';
            $message = is_array($message) ? json_encode($message) : $message;
            $content = "[{$level}] " . date('Y-m-d H:i:s') . " --> {$message}\n";
            if (IS_CLI) {
                echo $content . "\n";
            }

            return file_put_contents($file, $content, FILE_APPEND) !== false;
        }

        return true;
    }

    /**
     * 获取当前 url （不含 query 参数及文件名）
     * @return string
     */
    public static function getCurrentURL()
    {
        $url = self::getRequestScheme() . '://' . $_SERVER['HTTP_HOST'];
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
    public static function runRefreshThread()
    {
        Curl::get(Config::$url . 'index.php?job=refresh', [
            CURLOPT_TIMEOUT => 1,
        ]);
    }

    /**
     * 获取当前请求的协议
     * @return string
     */
    public static function getRequestScheme()
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
}