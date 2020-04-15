<?php

namespace YouzanCloudBoot\Daemon\Task;

use Exception;
use Youzan\Open\Config\EcommerceConfig;
use Youzan\Open\Token;
use YouzanCloudBoot\Component\BaseComponent;
use YouzanCloudBoot\Constant\CacheKey;
use YouzanCloudBoot\Facades\EnvFacade;
use YouzanCloudBoot\Facades\LogFacade;
use YouzanCloudBoot\Facades\RedisFacade;

class DaemonTokenTask extends BaseComponent
{

    public function handle(): void
    {
        try {
            $this->process();
        } catch (Exception $e) {
            LogFacade::err("DaemonTokenTask ex..." . $e->getTraceAsString());
        }
    }

    private function process()
    {
        // 1. 从Apollo拉取KdtId List
        $authorityIds = EnvFacade::get('cloud.auth.kdtid');
        if (empty($authorityIds)) {
            return;
        }

        $authorityIdArr = json_decode($authorityIds, true);
        if (!is_array($authorityIdArr)) {
            return;
        }

        // 2. 遍历 从Redis取值
        foreach ($authorityIdArr as $authorityId) {
            try {
                $key = sprintf(CacheKey::TOKEN, trim($authorityId));
                $this->refreshToken(RedisFacade::get($key));
            } catch (Exception $e) {
                LogFacade::err("DaemonTokenTask process refreshToken ex. authorityId:{$authorityId}, " . $e->getTraceAsString());
            }
        }

    }

    private function refreshToken($oldTokenStr)
    {
        if (empty($oldTokenStr) || !is_string($oldTokenStr)) {
            return;
        }

        $oldTokenArr = json_decode($oldTokenStr, true);
        if (!is_array($oldTokenArr) || !array_key_exists('refresh_token', $oldTokenArr)) {
            return;
        }

        //todo debug code
        $_SERVER[EcommerceConfig::ENV_PROXY_ENABLE] = false;
        $config = [];
        $envs = EnvFacade::get('SKYNET_ENVS');
        if (!empty($envs) && (strpos($envs, 'qabb') !== false)) {
            $config['baseUrl'] = 'http://bifrost-oauth.qa.s.qima-inc.com';
        }
        //todo debug code

        $newTokenArr = (new Token(
            EnvFacade::get('opensdk.clientId'), EnvFacade::get('opensdk.clientSecret')
        ))->refreshToken($oldTokenArr['refresh_token'], $config);

        if (is_array($newTokenArr) && array_key_exists('access_token', $newTokenArr)) {
            $key = sprintf(CacheKey::TOKEN, trim($newTokenArr['authority_id']));
            $setResp = RedisFacade::set($key, json_encode($newTokenArr));
        }
    }

}