<?php


namespace YouzanCloudBoot\Facades;


/**
 * PDOFactory 静态代理
 * 默认实现 @see \YouzanCloudBoot\Store\PDOFactory
 *
 * @method static null|\PDO buildMySQLInstance($host, $port, $username, $password, $options)
 * @method static null|\PDO buildBuiltinMySQLInstance($charset = 'utf8mb4')
 */
class PDOFactory extends Facade
{

    /**
     * 给静态代理设置服务名称
     * 子类必须覆盖这个方法
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'pdoFactory';
    }
}