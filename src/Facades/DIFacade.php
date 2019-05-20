<?php


namespace YouzanCloudBoot\Facades;


/**
 * Container 的静态代理
 * 默认实现参考 @see \Slim\Container
 * 接口定义请参考 PSR-11 @see \Psr\Container\ContainerInterface
 *
 * @method static mixed get(string $id)
 * @method static bool has(string $id)
 */
class DIFacade extends Facade
{

    /**
     * 给静态代理设置服务名称
     * 子类必须覆盖这个方法
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'container';
    }

    public static function self()
    {
        return self::$app->getContainer();
    }
}