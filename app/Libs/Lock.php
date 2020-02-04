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
        $lock = Storage::get("app/{$name}.lock");
        if ($lock === false) {
            return false;
        }

        return ($lock > time() || $lock == 0);
    }

    /**
     * 创建锁
     * @param string $name
     * @param int    $expire 自动过期时间（秒）
     * @return bool 创建失败或锁已存在时返回 false
     */
    public static function create($name, $expire = 0)
    {
        if (self::check($name) === false) {
            return Storage::save("app/{$name}.lock", ($expire ? (time() + $expire) : 0));
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
        return Storage::remove("app/{$name}.lock");
    }

    /**
     * 强制创建锁
     * @param string $name 锁名称
     * @param int    $expire 自动过期时间（秒）
     * @return bool
     */
    public static function forceCreate($name, $expire)
    {
        return Storage::save("app/{$name}.lock", ($expire ? (time() + $expire) : 0));
    }
}