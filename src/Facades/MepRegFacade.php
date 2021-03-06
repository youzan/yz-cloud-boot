<?php


namespace YouzanCloudBoot\Facades;


/**
 * MepRegistry 静态代理
 * 默认实现 @see \YouzanCloudBoot\ExtensionPoint\MepRegistry
 *
 * @method static void register(string $topic, string $class): void
 * @method static \YouzanCloudBoot\Component\BaseComponent getBean(string $topic): \YouzanCloudBoot\Component\BaseComponent
 */
class MepRegFacade extends Facade
{

    /**
     * 给静态代理设置服务名称
     * 子类必须覆盖这个方法
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'mepRegistry';
    }
}