<?php


namespace YouzanCloudBoot\Facades;


/**
 * Response 静态代理
 * 默认实现 @see \Slim\Http\Response
 * 接口定义 @see \Psr\Http\Message\ResponseInterface
 *
 * @method static int getStatusCode();
 * @method static \Slim\Http\Response withStatus($code, $reasonPhrase = '');
 * @method static string getReasonPhrase()
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