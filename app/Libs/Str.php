<?php

namespace app\Libs;

/**
 * 字符串类
 */
class Str
{
    /**
     * key-value 转为 KeyValue
     * @param $value
     * @return string
     */
    public static function studly($value)
    {
        $words = explode(' ', str_replace(['-', '_'], ' ', $value));

        $studlyWords = array_map(function ($word) {
            return ucfirst($word);
        }, $words);

        return implode($studlyWords);
    }

    /**
     * 生成指定数量的随机字符串
     * @param int $length 长度
     * @param string $chars 从这些字符中随机选择。默认为 0123456789abcdefghijklmnopqrstuvwxyz
     * @return string
     */
    public static function random($length, $chars = '0123456789abcdefghijklmnopqrstuvwxyz')
    {
        $charsLength = strlen($chars);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $chars[rand(0, $charsLength - 1)];
        }

        return $randomString;
    }
}