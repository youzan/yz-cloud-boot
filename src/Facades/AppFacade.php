<?php


namespace YouzanCloudBoot\Facades;


/**
 * App 静态代理
 * 所有方法请参考 @see \Slim\App
 *
 * @method static \Psr\Container\ContainerInterface getContainer()
 * @method static add(callable $callable)
 * @method static \Slim\Interfaces\RouteInterface get(string $pattern, callable $callable)
 * @method static \Slim\Interfaces\RouteInterface post(string $pattern, callable $callable)
 * @method static \Slim\Interfaces\RouteInterface put(string $pattern, callable $callable)
 * @method static \Slim\Interfaces\RouteInterface patch(string $pattern, callable $callable)
 * @method static \Slim\Interfaces\RouteInterface delete(string $pattern, callable $callable)
 * @method static \Slim\Interfaces\RouteInterface options(string $pattern, callable $callable)
 * @method static \Slim\Interfaces\RouteInterface any(string $pattern, callable $callable)
 * @method static \Slim\Interfaces\RouteInterface map(array $methods, string $pattern, callable $callable)
 * @method static \Slim\Interfaces\RouteInterface redirect(string $from, string $to, int $statusCode)
 * @method static \Slim\Interfaces\RouteGroupInterface group(string $pattern, callable $callable)
 * @method static \Psr\Http\Message\ResponseInterface run(bool $silent)
 */
class AppFacade extends Facade
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

class_alias(AppFacade::class, 'YouzanCloudBoot\Facades\RouteFacade');