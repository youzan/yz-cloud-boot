<?php

namespace YouzanCloudBoot\ExtensionPoint;

use YouzanCloudBoot\Component\BaseComponent;
use YouzanCloudBoot\Exception\BeanRegistryFailureException;

class BepRegistry extends BaseComponent
{

    private $beanPool = [];

    public function register($bepValue, $class, $bepTag = null): void
    {
        if ($this->checkBeanDefinitionExists($bepValue, $bepTag)) {
            throw new BeanRegistryFailureException('The specific bep value has been registered');
        }

        /**
         * 这里不对这个类是否存在做检查，提高性能
         */

        $this->beanPool[$this->getBeanDefinitionKey($bepValue, $bepTag)] = ['class' => $class, 'tag' => $bepTag];
    }

    public function getBean($bepValue, $bepTag = null): BaseComponent
    {
        $beanDef = $this->getBeanDefinition($bepValue, $bepTag);

        $class = $beanDef['class'];
        $tag = $beanDef['tag'];

        $inst = new $class($this->getContainer());

        return $inst;
    }

    private function getBeanDefinition($bepValue, $bepTag): array
    {
        if (!$this->checkBeanDefinitionExists($bepValue, $bepTag)) {
            throw new BeanRegistryFailureException('bep impl not exists');
        }

        return $this->beanPool[$this->getBeanDefinitionKey($bepValue, $bepTag)];
    }

    private function checkBeanDefinitionExists($bepValue, $bepTag): bool
    {
        return isset($this->beanPool[$this->getBeanDefinitionKey($bepValue, $bepTag)]);
    }

    private function getBeanDefinitionKey($bepValue, $bepTag): string
    {
        if (!isset($bepValue) or empty($bepValue)) {
            throw new BeanRegistryFailureException('Bep value cannot be empty');
        }
        return $bepValue . $this->getBeanTagSuffix($bepTag);
    }

    private function getBeanTagSuffix($bepTag): string
    {
        if (!empty($bepTag)) {
            return '_' . $bepTag;
        }
        return '';
    }

}