<?php


namespace YouzanCloudBoot\Facades;


/**
 * 经过有赞云对外网关的Http客户端的静态代理
 * 默认实现 @see \YouzanCloudBoot\Http\HttpClientWrapper
 *
 * @method static \YouzanCloudBoot\Http\HttpClientResponse get(string $url, array $headers = null)
 * @method static \YouzanCloudBoot\Http\HttpClientResponse post(string $url, array $headers = null, $body = null)
 * @method static \YouzanCloudBoot\Http\HttpClientResponse put(string $url, array $headers = null, $body = null)
 * @method static \YouzanCloudBoot\Http\HttpClientResponse delete(string $url, array $headers = null, $body = null)
 */
class HttpFacade extends Facade
{

    /**
     * 给静态代理设置服务名称
     * 子类必须覆盖这个方法
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'httpClient';
    }
}