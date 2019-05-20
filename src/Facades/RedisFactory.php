<?php


namespace YouzanCloudBoot\Facades;


/**
 * RedisFactory 静态代理
 * 默认实现 @see \YouzanCloudBoot\Store\RedisFactory
 *
 * @method static null|\Redis buildRedisInstance($host, $port)
 * @method static null|\Redis buildBuiltinRedisInstance()
 */
class RedisFactory extends Facade
{

    /**
     * 给静态代理设置服务名称
     * 子类必须覆盖这个方法
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'redisFactory';
    }
}