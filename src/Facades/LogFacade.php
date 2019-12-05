<?php


namespace YouzanCloudBoot\Facades;


/**
 * Logger 静态代理
 * 默认实现 @see \Monolog\Logger
 * 接口定义 @see \Psr\Log\LoggerInterface
 *
 * @method static void emergency(string $message, array $context = array());
 * @method static void emerg(string $message, array $context = array());
 * @method static void alert(string $message, array $context = array());
 * @method static void critical(string $message, array $context = array());
 * @method static void crit(string $message, array $context = array());
 * @method static void error(string $message, array $context = array());
 * @method static void err(string $message, array $context = array());
 * @method static void warning(string $message, array $context = array());
 * @method static void warn(string $message, array $context = array());
 * @method static void notice(string $message, array $context = array());
 * @method static void info(string $message, array $context = array());
 * @method static void debug(string $message, array $context = array());
 * @method static void log($level, string $message, array $context = array());
 */
class LogFacade extends Facade
{

    private const MAX_LENGTH = 10240;

    /**
     * 给静态代理设置服务名称
     * 子类必须覆盖这个方法
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'logger';
    }

    public static function __callStatic($method, $arguments)
    {
        if (is_array($arguments) && isset($arguments[0])) {
            if (strlen(json_encode($arguments)) > self::MAX_LENGTH) {
                $arguments = [substr($arguments[0], 0, self::MAX_LENGTH)];
            }
        }

        return static::self()->$method(...$arguments);
    }
}