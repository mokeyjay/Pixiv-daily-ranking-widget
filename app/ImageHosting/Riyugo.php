<?php

namespace app\ImageHosting;

use app\Libs\Config;
use app\Libs\Curl;
use app\Libs\Log;
use app\Libs\Str;

/**
 * 薄荷图床（国内，香港腾讯云）
 * Class riyugo
 * @package app\ImageHosting
 * @url https://riyugo.com/
 */
class Riyugo extends ImageHosting
{
    public function upload($path)
    {
        $config = Config::$image_hosting_extend['riyugo'];
        foreach (['url', 'unique_id', 'token'] as $key) {
            if (empty($config[$key])) {
                Log::write('[薄荷图床]读取账号配置失败');
                return false;
            }
        }

        $data = [
            'name' => pathinfo($path, PATHINFO_BASENAME),
            'uuid' => 'o_1g' . Str::random(27),
            'uploadPath' => $config['upload_path'],
            'mode' => 1,
            'file' => Curl::getCurlFile($path),
        ];
        $result = Curl::post(rtrim($config['url']) . '/file.php', $data, [
            CURLOPT_HTTPHEADER => [
                'accept: */*',
                'referer: ' . $config['url'],
                'origin: ' . $config['url'],
            ],
            CURLOPT_COOKIE => sprintf('frontendlogin=y; name-mode=_isRenameMode; filemanager%s=%s', $config['unique_id'], $config['token']),
        ]);

        Log::write('[薄荷图床]上传：' . json_encode($data));
        Log::write('[薄荷图床]返回：' . $result);

        $json = json_decode($result, true);
        if (isset($json['result']) && $json['result'] == 'success' && !empty($json['url'])) {
            return $json['url'];
        }

        Log::write('[薄荷图床]上传失败', 'ERROR');
        return false;
    }
}