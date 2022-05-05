<?php

namespace App\Libs;

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
}