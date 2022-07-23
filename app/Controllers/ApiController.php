<?php

namespace app\Controllers;

use app\Libs\Storage;

/**
 * 接口
 */
class ApiController extends Controller
{
    public function __construct()
    {
        header('content-type: application/json');

        $this->setAutoCorsHeader();
    }

    /**
     * 自动设置跨域头
     * @return void
     */
    private function setAutoCorsHeader()
    {
        if (!isset($_SERVER['HTTP_ORIGIN']) && !isset($_SERVER['HTTP_REFERER'])) {
            return;
        }

        $origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : $_SERVER['HTTP_REFERER'];

        header('Access-Control-Allow-Origin: ' . $origin);
    }

    /**
     * 源数据（未使用图床）
     * @return void
     */
    public function sourceJson()
    {
        echo json_encode(Storage::getJson('source') ?: []);
    }

    /**
     * 处理后的数据（已使用图床）
     * @return void
     */
    public function pixivJson()
    {
        echo json_encode(Storage::getJson('pixiv') ?: []);
    }
}