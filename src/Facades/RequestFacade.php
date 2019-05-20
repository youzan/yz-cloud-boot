<?php


namespace YouzanCloudBoot\Facades;


/**
 * Request 静态代理
 * 默认实现 @see \Slim\Http\Request
 * 接口定义 @see \Psr\Http\Message\ServerRequestInterface
 *
 * @method static array getServerParams();
 * @method static array getCookieParams();
 * @method static \Slim\Http\Request withCookieParams(array $cookies);
 * @method static array getQueryParams();
 * @method static \Slim\Http\Request withQueryParams(array $query);
 * @method static array getUploadedFiles();
 * @method static \Slim\Http\Request withUploadedFiles(array $uploadedFiles);
 * @method static null|array|object getParsedBody();
 * @method static \Slim\Http\Request withParsedBody($data);
 * @method static array getAttributes();
 * @method static string getAttribute($name, $default = null);
 * @method static \Slim\Http\Request withAttribute($name, $value);
 * @method static \Slim\Http\Request withoutAttribute($name);
 */
class RequestFacade extends Facade
{

    /**
     * 给静态代理设置服务名称
     * 子类必须覆盖这个方法
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'request';
    }
}