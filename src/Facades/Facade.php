<?php


namespace YouzanCloudBoot\Facades;


use Slim\App;

abstract class Facade
{

    /**
     * @var App
     */
    protected static $app;

    public static function setFacadeApplication(App $app)
    {
        self::$app = $app;
    }

    /**
     * 静态代理魔术方法
     *
     * @param $method
     * @param $arguments
     * @return mixed
     */
    public static function __callStatic($method, $arguments)
    {
        return static::self()->$method(...$arguments);
    }

    /**
     * 给静态代理设置服务名称
     * 子类必须覆盖这个方法
     *
     * @return string
     */
    abstract protected static function getFacadeAccessor() : string;

    /**
     * 返回代理的实例
     *
     * @return mixed
     */
    public static function self()
    {
        return Facade::$app->getContainer()->get(static::getFacadeAccessor());
    }


}