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
        $url = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'];
        if ($_SERVER['SERVER_PORT'] != '80') {
            $url .= ':' . $_SERVER['SERVER_PORT'];
        }
        $url .= pathinfo($_SERVER['DOCUMENT_URI'], PATHINFO_DIRNAME);
        $url = rtrim($url, '/') . '/';

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