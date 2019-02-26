<?php

namespace YouzanCloudBoot\Controller;

use Psr\Container\ContainerInterface;

abstract class BaseController
{

    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

}