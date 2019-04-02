<?php

namespace app\Libs;

/**
 * 锁
 * Class Lock
 * @package app\Libs
 */
class Lock
{
    /**
     * 锁是否有效
     * @param string $name
     * @return bool
     */
    public static function check($name)
    {
        $lock = Storage::get(".{$name}lock");
        if (!$lock) {
            return false;
        }

        if (!isset($lock['time'])) {
            return false;
        }
        if ($lock['time'] > time() || $lock['time'] == 0) {
            return true;
        }
        return false;
    }

    /**
     * 创建锁
     * @param string $name
     * @param int    $expire 自动过期时间（秒）
     * @return bool
     */
    public static function create($name, $expire = 0)
    {
        if (self::check($name) == false) {
            return Storage::save(".{$name}lock", ['time' => $expire ? (time() + $expire) : 0]);
        }
        return false;
    }

    /**
     * 移除锁
     * @param string $name
     * @return bool
     */
    public static function remove($name)
    {
        return Storage::remove(".{$name}lock");
    }
}