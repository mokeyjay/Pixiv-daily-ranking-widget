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
     * @param bool   $includeCookie 同时返回 cookie
     * @return bool|string|array $includeCookie 为 true 时返回 ['cookie' => '', 'html' => ''] 数组
     */
    public static function get($url, $opt = [], $includeCookie = false)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36 Edg/109.0.1518.52');

        Config::$proxy && curl_setopt($ch, CURLOPT_PROXY, Config::$proxy);
        $includeCookie && curl_setopt($ch, CURLOPT_HEADER, true);

        if (count($opt)) {
            curl_setopt_array($ch, $opt);
        }

        $data = curl_exec($ch);
        curl_close($ch);

        if ($includeCookie) {
            $data = explode("\r\n\r\n", $data);

            $header = array_shift($data);
            $html = implode("\r\n\r\n", $data);

            preg_match_all("/set-cookie:([^\r\n]*)/i", $header, $matches);

            $cookie = '';
            if (!empty($matches[1])) {
                $cookieItem = [];
                foreach ($matches[1] as $item) {
                    $cookieItem[] = explode(';', trim($item))[0];
                }
                $cookie = implode('; ', $cookieItem);
            }

            return compact('cookie', 'html');
        }

        return $data;
    }

    /**
     * post请求
     * @param string       $url
     * @param array|string $postData
     * @param array        $opt
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