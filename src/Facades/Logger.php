<?php


namespace YouzanCloudBoot\Facades;


/**
 * Logger 静态代理
 * 默认实现 @see \Monolog\Logger
 * 接口定义 @see \Psr\Log\LoggerInterface
 *
 * @method static emergency(string $message, array $context = array());
 * @method static emerg(string $message, array $context = array());
 * @method static alert(string $message, array $context = array());
 * @method static critical(string $message, array $context = array());
 * @method static crit(string $message, array $context = array());
 * @method static error(string $message, array $context = array());
 * @method static err(string $message, array $context = array());
 * @method static warning(string $message, array $context = array());
 * @method static warn(string $message, array $context = array());
 * @method static notice(string $message, array $context = array());
 * @method static info(string $message, array $context = array());
 * @method static debug(string $message, array $context = array());
 * @method static log($level, string $message, array $context = array());
 */
class Logger extends Facade
{

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
}