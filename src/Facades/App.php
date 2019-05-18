<?php


namespace YouzanCloudBoot\Facades;


/**
 * App 静态代理
 * 所有方法请参考 @see \Slim\App
 *
 * @method static getContainer()
 * @method static add(callable $callable)
 * @method static get(string $pattern, callable $callable)
 * @method static post(string $pattern, callable $callable)
 * @method static put(string $pattern, callable $callable)
 * @method static patch(string $pattern, callable $callable)
 * @method static delete(string $pattern, callable $callable)
 * @method static options(string $pattern, callable $callable)
 * @method static any(string $pattern, callable $callable)
 * @method static map(array $methods, string $pattern, callable $callable)
 * @method static redirect(string $from, string $to, int $statusCode)
 * @method static group(string $pattern, callable $callable)
 * @method static run(bool $silent)
 */
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

class_alias(App::class, 'YouzanCloudBoot\Facades\Route');