<?php

namespace app\Libs;

/**
 * 日志类
 */
class Log
{
    const LEVEL_ERROR = 'ERROR';

    /**
     * 写日志
     * @param string|array $message
     * @param string       $level
     * @return bool
     */
    public static function write($message, $level = 'DEBUG')
    {
        $level = strtoupper($level);

        if (!is_array(Config::$log_level) || !in_array($level, Config::$log_level)) {
            return false;
        }

        $message = is_array($message) ? json_encode($message) : $message;
        $content = "[{$level}] " . date('Y-m-d H:i:s') . " --> {$message}\n";
        if (IS_CLI) {
            echo $content . "\n";
        }

        $file = STORAGE_PATH . 'logs/' . date('Ymd') . (IS_CLI ? '-cli' : '') . '.log';

        return file_put_contents($file, $content, FILE_APPEND) !== false;
    }
}