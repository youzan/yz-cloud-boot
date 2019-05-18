<?php


namespace YouzanCloudBoot\Facades;


/**
 * Response 静态代理
 * 默认实现 @see \Slim\Http\Response
 * 接口定义 @see \Psr\Http\Message\ResponseInterface
 *
 * @method static getStatusCode();
 * @method static withStatus($code, $reasonPhrase = '');
 * @method static getReasonPhrase()
 */
class Response extends Facade
{

    /**
     * 给静态代理设置服务名称
     * 子类必须覆盖这个方法
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'response';
    }
}