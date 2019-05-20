<?php


namespace YouzanCloudBoot\Facades;


/**
 * YZCMySQL 静态代理
 * 在标准实现下，实际上所有静态方法会被转发到一个 @see \PDO 类的实例
 * 请参考 * @see \PDO
 *
 * @method static \PDOStatement|bool prepare($statement, array $driver_options = array());
 * @method static bool beginTransaction();
 * @method static bool commit();
 * @method static bool rollBack();
 * @method static bool inTransaction();
 * @method static bool setAttribute($attribute, $value);
 * @method static bool exec($statement);
 * @method static \PDOStatement|false query($statement, $mode = \PDO::ATTR_DEFAULT_FETCH_MODE, $arg3 = null, array $ctorargs = array());
 * @method static string lastInsertId($name = null);
 * @method static string errorCode();
 * @method static array errorInfo();
 * @method static mixed getAttribute($attribute);
 * @method static string quote($string, $parameter_type = \PDO::PARAM_STR);
 */
class DBFacade extends Facade
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