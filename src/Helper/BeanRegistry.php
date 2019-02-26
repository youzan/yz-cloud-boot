<?php

namespace YouzanCloudBoot\Helper;

use Psr\Container\ContainerInterface;
use ReflectionClass;
use YouzanCloudBoot\Exception\BeanRegistryFailureException;

class BeanRegistry
{

    private $beanPool = [];

    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function registerBean($beanName, $class, $tag = null)
    {
        if (isset($this->beanPool[$beanName])) {
            throw new BeanRegistryFailureException('The specific bean name has been registered');
        }

        if (class_exists($class, true)) {
            $ref = new ReflectionClass($class);
        } else {
            throw new BeanRegistryFailureException('Target class not exists');
        }

        $this->beanPool[$beanName] = ['class' => $class, 'tag' => $tag];
    }

    public function getBean($beanName)
    {
        if (!isset($beanName)) {
            throw new BeanRegistryFailureException('Bean name cannot be empty');
        }

        if (!isset($this->beanPool[$beanName])) {
            throw new BeanRegistryFailureException('Bean not exists');
        }

        $beanDef = $this->beanPool[$beanName];

        $class = $beanDef['class'];
        $tag = $beanDef['tag'];

        $inst = new $class($this->container);

        return $inst;
    }

}