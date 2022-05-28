<?php

namespace app;

use app\Jobs\Job;
use app\Libs\Config;
use app\Libs\Log;
use app\Libs\Str;

class App
{
    /**
     * 应用初始化
     */
    protected static function init()
    {
        Config::init();

        // 注册全局错误捕捉
        set_exception_handler(function ($exception) {
            Log::write($exception->getMessage(), 'ERROR');
            http_response_code(500);
            die;
        });
    }

    public static function run()
    {
        self::init();

        self::job();

        self::route();
    }

    /**
     * 运行任务
     * @throws \Exception
     */
    protected static function job()
    {
        $opt = getopt('j:');
        if (empty($_GET['job']) && empty($opt['j'])) {
            return;
        }

        $jobName = !empty($_GET['job']) ? $_GET['job'] : $opt['j'];
        $job = Job::make($jobName);

        if (!empty($_GET['job']) && $job->onlyActivateByCli) {
            throw new \Exception("任务 {$jobName} 只能通过 cli 触发");
        }

        if (!$job) {
            throw new \Exception("任务 {$jobName} 加载失败");
        }

        set_time_limit(0);
        if ($job->run()) {
            Log::write("任务 {$jobName} 执行完毕");
            echo "任务 {$jobName} 执行完毕";
        } else {
            throw new \Exception("任务 {$jobName} 执行失败：{$job->getErrorMsg()}");
        }

        exit;
    }

    /**
     * 路由
     */
    protected static function route()
    {
        $route = isset($_GET['r']) ? $_GET['r'] : 'index';
        $route = explode('/', $route);

        $controller = Str::studly(array_shift($route) ?: 'index');
        $method = array_pop($route) ?: 'index';

        $class = "app\\Controllers\\{$controller}Controller";

        if (!class_exists($class) || !is_callable([$class, $method])) {
            Log::write('错误的路由：' . (isset($_GET['r']) ? $_GET['r'] : 'index'), Log::LEVEL_ERROR);

            http_response_code(404);
            die;
        }

        (new $class)->$method();
    }
}