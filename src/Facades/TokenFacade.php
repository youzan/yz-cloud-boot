<?php

namespace YouzanCloudBoot\Facades;

/**
 * TokenUtil 静态代理
 * 默认实现参考 @see \YouzanCloudBoot\Util\TokenUtil
 *
 * @method static string getAccessToken($authorityId)
 */
class TokenFacade extends Facade
{

    /**
     * 给静态代理设置服务名称
     * 子类必须覆盖这个方法
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'tokenUtil';
    }
}