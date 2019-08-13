<?php

namespace YouzanCloudBoot\ExtensionPoint;

use YouzanCloudBoot\Component\BaseComponent;
use YouzanCloudBoot\Exception\TopicRegistryFailureException;

class MepRegistry extends BaseComponent
{

    private $topicPool = [];

    public function register($topic, $class): void
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
        $inst = new $class($this->getContainer());

        return $inst;
    }

    public function checkTopicDefinitionExists($topic): bool
    {
        return isset($this->topicPool[$topic]);
    }

}