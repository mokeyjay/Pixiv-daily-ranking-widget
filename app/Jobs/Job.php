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
    // 是否只能通过 cli 触发
    public $onlyActivateByCli = false;

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