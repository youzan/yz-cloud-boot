<?php


namespace YouzanCloudBoot\Facades;


class App extends Facade
{

    /**
     * 给静态代理设置服务名称
     * 子类必须覆盖这个方法
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'app';
    }

    public static function self()
    {
        return self::$app;
    }
}