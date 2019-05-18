<?php


namespace YouzanCloudBoot\Facades;


/**
 * PDOFactory 静态代理
 * 默认实现 @see \YouzanCloudBoot\Store\PDOFactory
 *
 * @method static buildMySQLInstance($host, $port, $username, $password, $options): PDO
 * @method static buildBuiltinMySQLInstance($charset = 'utf8mb4'): ?PDO
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