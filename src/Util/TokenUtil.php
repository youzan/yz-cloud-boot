<?php

namespace YouzanCloudBoot\Util;

use Throwable;
use Youzan\Open\Token;
use YouzanCloudBoot\Component\BaseComponent;
use YouzanCloudBoot\Constant\CacheKey;
use YouzanCloudBoot\Exception\TokenException;
use YouzanCloudBoot\Facades\EnvFacade;
use YouzanCloudBoot\Facades\LogFacade;
use YouzanCloudBoot\Facades\RedisFacade;

class TokenUtil extends BaseComponent
{

    /**
     * 获取授权主体accessToken
     * (仅支持已启用code接管 且历史授权主体已全部重新获取code的 工具型应用使用)
     *
     * @param $authorityId
     * @return string
     * @throws TokenException
     */
    public function getAccessToken($authorityId): string
    {
        $key = sprintf(CacheKey::TOKEN, trim($authorityId));
        $cacheValue = RedisFacade::get($key);

        if (empty($cacheValue) || !is_string($cacheValue)) {
            throw new TokenException('token not exists in redis');
        }

        $tokenArr = json_decode($cacheValue, true);
        if (is_array($tokenArr) && array_key_exists('access_token', $tokenArr) && is_string($tokenArr['access_token'])) {
            return $tokenArr['access_token'];
        }

        throw new TokenException('token valid');
    }


    /**
     * 工具型 code换取token
     * @param string $code
     * @param int $reties
     * @return array
     * @throws TokenException
     */
    public function code2Token(string $code, $reties = 3): array
    {
        if ($reties < 0) {
            throw new TokenException('TokenUtil code2Token. exceeds the maximum retries');
        }

        try {
            return $this->code2TokenProcess($code);
        } catch (TokenException $ce) {
            throw $ce;
        } catch (Throwable $e) {
            if ($reties > 0) {
                return $this->code2Token($code, --$reties);
            }
            throw new TokenException("TokenUtil code2Token. exception {$e->getMessage()}");
        }
    }


    /**
     * 工具型 code换取token 业务逻辑
     * @param string $code
     * @return array
     * @throws TokenException
     */
    private function code2TokenProcess(string $code): array
    {
        LogFacade::info("[code2TokenProcess] code:{$code}");

        //todo debug code
        putenv('youzan_proxy_enable:false');
        $config = [];
        $envs = EnvFacade::get('SKYNET_ENVS');
        if (!empty($envs) && (strpos($envs, 'qabb') !== false)) {
            $config['baseUrl'] = 'http://bifrost-oauth.qa.s.qima-inc.com';
        }
        //todo debug code

        $tokenArr = (new Token(
            EnvFacade::get('opensdk.clientId'), EnvFacade::get('opensdk.clientSecret')
        ))->getToolAppToken($code, $config);

        LogFacade::info("[code2TokenProcess] tokenArr", $tokenArr);

        if (!is_array($tokenArr) || !isset($tokenArr['access_token'])) {
            throw new TokenException($tokenArr['message'] ?? 'code2Token fail');
        }

        $key = sprintf(CacheKey::TOKEN, trim($tokenArr['authority_id']));
        $setResp = RedisFacade::set($key, json_encode($tokenArr));
        LogFacade::info("TokenUtil code2TokenProcess. redis set: {$setResp}", $tokenArr);

        return $tokenArr;
    }

}