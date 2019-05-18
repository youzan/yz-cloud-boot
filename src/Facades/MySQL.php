<?php


namespace YouzanCloudBoot\Facades;


/**
 * YZCMySQL 静态代理
 * 在标准实现下，实际上所有静态方法会被转发到一个 @see \PDO 类的实例
 * 请参考 * @see \PDO
 *
 * @method static prepare($statement, array $driver_options = array());
 * @method static beginTransaction();
 * @method static commit();
 * @method static rollBack();
 * @method static inTransaction();
 * @method static setAttribute($attribute, $value);
 * @method static exec($statement);
 * @method static query($statement, $mode = \PDO::ATTR_DEFAULT_FETCH_MODE, $arg3 = null, array $ctorargs = array());
 * @method static lastInsertId($name = null);
 * @method static errorCode();
 * @method static errorInfo();
 * @method static getAttribute($attribute);
 * @method static quote($string, $parameter_type = \PDO::PARAM_STR);
 */
class MySQL extends Facade
{

    /**
     * 给静态代理设置服务名称
     * 子类必须覆盖这个方法
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'yzcMysql';
    }
}