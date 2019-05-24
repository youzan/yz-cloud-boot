<?php


namespace YouzanCloudBoot\Facades;


/**
 * BepRegistry 静态代理
 * 所有方法请参考 @see \YouzanCloudBoot\ExtensionPoint\BepRegistry
 *
 * @method static void register(string $bepValue, string $class, ?string $bepTag = null)
 * @method static \YouzanCloudBoot\Component\BaseComponent getBean(string $bepValue, ?string $bepTag = null)
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
        return 'bepRegistry';
    }
}