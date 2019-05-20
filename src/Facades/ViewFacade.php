<?php


namespace YouzanCloudBoot\Facades;


use ArrayIterator;
use Psr\Http\Message\ResponseInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\Loader\LoaderInterface;


/**
 * view 静态代理
 * 在标准实现下，实际上所有静态方法会被转发到一个 \Slim\Views\Twig 类的实例
 * 请参考 * @see \Slim\Views\Twig
 *
 * @method static string fetch(string $template, array $data = []) ;
 * @method static string fetchBlock(string $template, string $block, array $data = []) ;
 * @method static string fetchFromString(string $string = "", array $data = []) ;
 * @method static ResponseInterface render(ResponseInterface $response, string $template, array $data = []) ;
 * @method static FilesystemLoader createLoader(array $paths) ;
 * @method static LoaderInterface getLoader() ;
 * @method static Environment getEnvironment() ;
 * @method static bool offsetExists(string $key) ;
 * @method static mixed offsetGet(string $key) ;
 * @method static void offsetSet(string $key, mixed $value) ;
 * @method static void offsetUnset(string $key) ;
 * @method static int count() ;
 * @method static ArrayIterator getIterator() ;
 */
class ViewFacade extends Facade
{

    /**
     * 给静态代理设置服务名称
     * 子类必须覆盖这个方法
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'view';
    }
}