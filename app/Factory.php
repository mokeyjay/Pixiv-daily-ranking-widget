<?php

namespace app;

/**
 * Class Factory
 * @package app
 */
class Factory
{
    public $errorMsg = '';

    /**
     * @param string $name   完整类名
     * @param array  $config 参数
     * @return mixed
     */
    public static function make($name, array $config = [])
    {
        try {
            return new $name($config);
        } catch (\Exception $e) {
            return false;
        }

    }

    /**
     * 获取错误信息
     * @return string
     */
    public function getErrorMsg()
    {
        return $this->errorMsg;
    }
}