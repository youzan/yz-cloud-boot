<?php

namespace YouzanCloudBoot\CronJob\Registry;

use YouzanCloudBoot\Component\BaseComponent;

class TimingRegistry extends BaseComponent
{

    private $beanPool = [];

    /**
     * register
     * @param string $name
     * @param callable $callback
     * @param float $timeInterval
     */
    public function register(string $name, callable $callback, float $timeInterval): void
    {
        $this->beanPool[$name] = ['timeInterval' => $timeInterval, 'callback' => $callback];
    }


    public function getBeanPool(): array
    {
        return $this->beanPool;
    }

}