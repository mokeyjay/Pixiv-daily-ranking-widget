<?php

namespace app\Jobs;

use app\Factory;

/**
 * 抽象 任务类
 * Class Job
 * @package app\Jobs
 */
abstract class Job extends Factory
{
    /**
     * @param string $name
     * @param array  $config
     * @return self
     */
    public static function make($name, array $config = [])
    {
        $name = '\\app\\Jobs\\' . ucfirst(strtolower($name));
        return parent::make($name, $config);
    }

    /**
     * 执行任务
     * @return bool
     */
    abstract public function run();
}