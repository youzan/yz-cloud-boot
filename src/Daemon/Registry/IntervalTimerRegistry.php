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
     * @param array $args
     * @param bool $persistent
     */
    private function register(string $name, callable $callback, float $timeInterval, array $args = [], bool $persistent = true): void
    {
        $this->beanPool[$name] = ['callback' => $callback, 'timeInterval' => $timeInterval, 'args' => $args, 'persistent' => $persistent];
    }


    private function initTask()
    {
        $this->register(
            'yz_cloud_boot_apollo_once',
            [new \YouzanCloudBoot\Daemon\Task\DaemonApolloTask($this->getContainer()), 'handle'],
            0.1,
            [],
            false
        );

        $this->register(
            'yz_cloud_boot_apollo_loop',
            [new \YouzanCloudBoot\Daemon\Task\DaemonApolloTask($this->getContainer()), 'handle'],
            60
        );

        $this->register(
            'yz_cloud_boot_token',
            [new \YouzanCloudBoot\Daemon\Task\DaemonTokenTask($this->getContainer()), 'handle'],
            3600
        );
    }

}