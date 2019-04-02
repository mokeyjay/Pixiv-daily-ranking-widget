<?php

namespace app;

use app\Jobs\Job;
use app\Libs\Config;
use app\Libs\Lock;
use app\Libs\Pixiv;
use app\Libs\Tools;

class App
{
    /**
     * 应用初始化
     */
    protected static function init()
    {
        Config::init();

        // 注册全局错误捕捉
        set_exception_handler(function (\Exception $exception) {
            Tools::log($exception->getMessage(), 'ERROR');
            http_response_code(500);
            die;
        });
    }

    public static function run()
    {
        self::init();

        if (!empty($_GET['job'])) {
            self::job();
        }

        // 加载首页或loading页
        $pixivJson = Pixiv::getJson('pixiv');
        if ($pixivJson === false) {
            if (!Lock::check('refresh')) {
                Pixiv::runRefreshThread();
            }
            include APP_PATH . 'Views/loading.php';
        } else {
            include APP_PATH . 'Views/index.php';
        }
    }

    /**
     * 运行任务
     * @throws \Exception
     */
    protected static function job()
    {
        $jobName = ucfirst(strtolower($_GET['job']));
        $job = Job::make($jobName);
        if (!$job) {
            throw new \Exception("任务 {$jobName} 加载失败");
        }
        $reuslt = $job->run();
        if ($reuslt) {
            Tools::log("任务 {$jobName} 执行完毕");
        } else {
            throw new \Exception("任务 {$jobName} 执行失败：{$job->getErrorMsg()}");
        }

        exit;
    }
}