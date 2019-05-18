<?php


namespace YouzanCloudBoot\Facades;


/**
 * RedisFactory 静态代理
 * 默认实现 @see \YouzanCloudBoot\Store\RedisFactory
 *
 * @method static buildRedisInstance($host, $port): Redisc
 * @method static buildBuiltinRedisInstance(): ?Redisc
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