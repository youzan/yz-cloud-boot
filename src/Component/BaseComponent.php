<?php

namespace YouzanCloudBoot\Component;

use Monolog\Logger;
use Psr\Container\ContainerInterface;
use YouzanCloudBoot\Helper\EnvHelper;

abstract class BaseComponent
{

    protected $_container;

    protected $_logger;

    public function __construct(ContainerInterface $container)
    {
        $this->_container = $container;
    }

    /**
     * 获取日志记录器
     * @return Logger
     */
    protected function getLog(): Logger
    {
        return $this->_container->get('logger');
    }

    /**
     * 获取 DI 容器
     * @return ContainerInterface
     */
    protected function getContainer(): ContainerInterface
    {
        return $this->_container;
    }

    /**
     * 环境变量访问助手
     * @return EnvHelper
     */
    protected function getEnvHelper(): EnvHelper
    {
        return $this->_container->get('envHelper');
    }


}