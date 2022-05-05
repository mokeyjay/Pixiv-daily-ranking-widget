<?php

namespace app;

use app\Jobs\Job;
use app\Libs\Config;
use app\Libs\Log;
use App\Libs\Str;

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
            Log::write($exception->getMessage(), 'ERROR');
            http_response_code(500);
            die;
        });
    }

    public static function run()
    {
        self::init();

        $opt = getopt('j:');
        if (!empty($_GET['job']) || isset($opt['j'])) {
            self::job();
            return;
        }

        self::route();
    }

    /**
     * 运行任务
     * @throws \Exception
     */
    protected static function job()
    {
        $opt = getopt('j:');
        $jobName = isset($_GET['job']) ? $_GET['job'] : $opt['j'];
        $jobName = ucfirst(strtolower($jobName));
        $job = Job::make($jobName);
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
    }

    /**
     * 路由
     */
    protected static function route()
    {
        $route = isset($_GET['r']) ? $_GET['r'] : 'index';
        $route = explode('/', $route);

        $controller = Str::studly(array_shift($route) ?: 'index');
        $method = Str::studly(array_pop($route) ?: 'index');

        $class = "app\\Controllers\\{$controller}Controller";

        if (!class_exists($class) || !is_callable([$class, $method])) {
            Log::write('错误的路由：' . $_GET['r'], Log::LEVEL_ERROR);

            http_response_code(404);
            die;
        }

        (new $class)->$method();
    }
}