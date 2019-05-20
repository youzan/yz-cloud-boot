<?php


namespace YouzanCloudBoot\Facades;


/**
 * BeanRegistry 静态代理
 * 所有方法请参考 @see \YouzanCloudBoot\ExtensionPoint\BeanRegistry
 *
 * @method static void registerBean(string $beanName, string $class, ?string $beanTag = null)
 * @method static \YouzanCloudBoot\Component\BaseComponent getBean(string $beanName, ?string $beanTag = null)
 */
class BepRegFacade extends Facade
{

    /**
     * 给静态代理设置服务名称
     * 子类必须覆盖这个方法
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'beanRegistry';
    }
}