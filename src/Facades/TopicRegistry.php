<?php


namespace YouzanCloudBoot\Facades;


/**
 * Class TopicRegistry
 * @package YouzanCloudBoot\Facades
 *
 * @method static registerTopic(string $topic, string $class): void
 * @method static getBean(string $topic): \YouzanCloudBoot\Component\BaseComponent
 */
class TopicRegistry extends Facade
{

    /**
     * 给静态代理设置服务名称
     * 子类必须覆盖这个方法
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'topicRegistry';
    }
}