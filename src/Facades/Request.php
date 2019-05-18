<?php


namespace YouzanCloudBoot\Facades;


/**
 * Request 静态代理
 * 默认实现 @see \Slim\Http\Request
 * 接口定义 @see \Psr\Http\Message\ServerRequestInterface
 *
 * @method static getServerParams();
 * @method static getCookieParams();
 * @method static withCookieParams(array $cookies);
 * @method static getQueryParams();
 * @method static withQueryParams(array $query);
 * @method static getUploadedFiles();
 * @method static withUploadedFiles(array $uploadedFiles);
 * @method static getParsedBody();
 * @method static withParsedBody($data);
 * @method static getAttributes();
 * @method static getAttribute($name, $default = null);
 * @method static withAttribute($name, $value);
 * @method static withoutAttribute($name);
 */
class Request extends Facade
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