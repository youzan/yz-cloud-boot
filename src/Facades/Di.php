<?php


namespace YouzanCloudBoot\Facades;


class Di extends Facade
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