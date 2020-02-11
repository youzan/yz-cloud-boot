<?php

namespace YouzanCloudBoot\Daemon\Registry;

use Psr\Container\ContainerInterface;
use YouzanCloudBoot\Component\BaseComponent;

class IntervalTimerRegistry extends BaseComponent
{

    private $beanPool = [];


    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);

        $this->initTask();
    }


    public function getBeanPool(): array
    {
        return $this->beanPool;
    }


    /**
     * register
     * @param string $name
     * @param callable $callback
     * @param float $timeInterval
     */
    private function register(string $name, callable $callback, float $timeInterval): void
    {
        $this->beanPool[$name] = ['timeInterval' => $timeInterval, 'callback' => $callback];
    }


    private function initTask()
    {
        $this->register(
            'yz_cloud_boot_apollo',
            [new \YouzanCloudBoot\Daemon\Task\DaemonApolloTask($this->getContainer()), 'handle'],
            60
        );
    }

}