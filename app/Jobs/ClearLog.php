<?php
namespace app\Jobs;

use app\Libs\Log;
use app\Libs\Storage;

/**
 * 清除日志文件
 * 接受参数 -n，表示删除 n 天之前的日志。默认为 7
 */
class ClearLog extends Job
{
    public $onlyActivateByCli = true;

    public function run()
    {
        $opt = getopt('n::');
        $n = (!empty($opt['n']) && $opt['n'] > 0 && is_numeric($opt['n'])) ? intval($opt['n']) : 7;

        $time = strtotime("-{$n} days");
        $path = STORAGE_PATH . 'logs/';
        $deleteNum = 0;

        if ($dh = opendir($path)) {
            while (($file = readdir($dh)) !== false) {
                if (in_array($file, ['.', '..', '.gitignore'])) {
                    continue;
                }

                $file = $path . $file;
                if (filemtime($file) < $time) {
                    $deleteNum++;
                    Storage::deleteFile($file);
                }
            }
        }

        Log::write("共计清除日志文件 {$deleteNum} 个");

        return true;
    }
}