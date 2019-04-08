<?php

namespace YouzanCloudBoot\ExtensionPoint;

use Psr\Container\ContainerInterface;
use YouzanCloudBoot\Component\BaseComponent;
use YouzanCloudBoot\Exception\BeanRegistryFailureException;

class TopicRegistry
{

    private $topicPool = [];

    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function registerTopic($topic, $class): void
    {
        if ($this->checkTopicDefinitionExists($topic)) {
            throw new TopicRegistryFailureException('The specific topic name has been registered');
        }

        /**
         * 这里不对这个类是否存在做检查，提高性能
         */
        $this->topicPool[$topic] = $class;
    }

    public function getBean($topic): BaseComponent
    {
        $class = $this->topicPool[$topic];
        $inst = new $class($this->container);

        return $inst;
    }

    private function checkTopicDefinitionExists($topic): bool
    {
        return isset($this->topicPool[$topic]);
    }

}