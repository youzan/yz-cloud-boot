<?php

namespace YouzanCloudBoot\Bep;

use Psr\Container\ContainerInterface;
use YouzanCloudBoot\Exception\BeanRegistryFailureException;

class BeanRegistry
{

    private $beanPool = [];

    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function registerBean($beanName, $class, $beanTag = null)
    {
        if ($this->checkBeanDefinitionExists($beanName, $beanTag)) {
            throw new BeanRegistryFailureException('The specific bean name has been registered');
        }

        /**
         * 这里不对这个类是否存在做检查，提高性能
         */

        $this->beanPool[$this->getBeanDefinitionKey($beanName, $beanTag)] = ['class' => $class, 'tag' => $beanTag];
    }

    public function getBean($beanName, $beanTag = null)
    {
        $beanDef = $this->getBeanDefinition($beanName, $beanTag);

        $class = $beanDef['class'];
        $tag = $beanDef['tag'];

        $inst = new $class($this->container);

        return $inst;
    }

    private function getBeanDefinition($beanName, $beanTag)
    {
        if (!$this->checkBeanDefinitionExists($beanName, $beanTag)) {
            throw new BeanRegistryFailureException('Bean not exists');
        }

        return $this->beanPool[$this->getBeanDefinitionKey($beanName, $beanTag)];
    }

    private function checkBeanDefinitionExists($beanName, $beanTag)
    {
        return isset($this->beanPool[$this->getBeanDefinitionKey($beanName, $beanTag)]);
    }

    private function getBeanDefinitionKey($beanName, $beanTag)
    {
        if (!isset($beanName) or empty($beanName)) {
            throw new BeanRegistryFailureException('Bean name cannot be empty');
        }
        return $beanName . $this->getBeanTagSuffix($beanTag);
    }

    private function getBeanTagSuffix($beanTag)
    {
        if (isset($beanTag)) {
            return '_' . $beanTag;
        }
        return '';
    }

}