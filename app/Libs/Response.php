<?php

namespace app\Libs;

class Response
{
    /**
     * 从一段 http 响应中获取 content-length
     * @param string $response
     * @return false|int
     */
    public static function getContentLength($response)
    {
        $response = explode("\n", $response);
        foreach ($response as $line) {
            $line = trim($line);

            if (stripos($line, 'Content-Length: ') === 0) {
                $line = explode(': ', $line);
                if (!empty($line[1]) && is_numeric($line[1])) {
                    return (int)$line[1];
                }

                break;
            }
        }

        return false;
    }
}