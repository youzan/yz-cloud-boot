<?php

namespace YouzanCloudBoot\Controller;

use Monolog\Logger;
use Psr\Container\ContainerInterface;

abstract class BaseController
{

    protected $_container;

    protected $_logger;

    public function __construct(ContainerInterface $container)
    {
        $this->_container = $container;
    }

    /**
     * 日志
     * @return Logger
     */
    protected function getLog(): Logger
    {
        return $this->_container->get('logger');
    }

    /**
     * DI 容器
     * @return ContainerInterface
     */
    protected function getContainer(): ContainerInterface
    {
        return $this->_container;
    }


}