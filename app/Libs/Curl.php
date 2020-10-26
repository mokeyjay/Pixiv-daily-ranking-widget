<?php

namespace app\Libs;

/**
 * Curl请求类
 * Class Curl
 * @package app\Libs
 */
class Curl
{
    /**
     * get请求
     * @param string $url 请求目标
     * @param array  $opt curl参数
     * @return bool|string
     */
    public static function get($url, $opt = [])
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.111 Safari/537.36 Edg/86.0.622.51');
        if (Config::$proxy) {
            curl_setopt($ch, CURLOPT_PROXY, Config::$proxy);
        }

        if (count($opt)) {
            curl_setopt_array($ch, $opt);
        }

        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    /**
     * post请求
     * @param string $url
     * @param array  $postData
     * @param array  $opt
     * @return bool|string
     */
    public static function post($url, $postData, $opt = [])
    {
        $opt[CURLOPT_POST] = true;
        $opt[CURLOPT_POSTFIELDS] = $postData;

        return self::get($url, $opt);
    }

    /**
     * 获取curl file实例
     * @param string $path 文件路径
     * @return \CURLFile|string
     */
    public static function getCurlFile($path)
    {
        return class_exists('CURLFile') ? (new \CURLFile(realpath($path))) : ('@' . realpath($path));
    }
}