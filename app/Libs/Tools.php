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
     * 获取当前url
     * @return string
     */
    public static function getCurrentURL()
    {
        $url = $_SERVER['REQUEST_SCHEME'] . '://';
        if ($_SERVER['SERVER_PORT'] != '80') {
            $url .= $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . $_SERVER['REQUEST_URI'];
        } else {
            $url .= $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
        }
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
}