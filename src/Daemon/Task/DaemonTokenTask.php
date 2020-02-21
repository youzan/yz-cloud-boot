<?php

namespace YouzanCloudBoot\Daemon\Task;

use Exception;
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
        LogFacade::info("DaemonTokenTask begin...");

        try {
            $this->process();
        } catch (Exception $e) {
            LogFacade::err("DaemonTokenTask ex..." . $e->getTraceAsString());
        }

        LogFacade::info("DaemonTokenTask end...");
    }

    private function process()
    {
        // 1. 从Apollo拉取KdtId List
        $authorityIds = EnvFacade::get('grantIds');
        if (empty($authorityIds)) {
            LogFacade::info("DaemonTokenTask process. the authorityIds empty");
            return;
        }

        $authorityIdArr = json_decode($authorityIds, true);
        if (!is_array($authorityIdArr)) {
            LogFacade::info("DaemonTokenTask process. the authorityIds decode fail. " . $authorityIds);
            return;
        }

        // 2. 遍历 从Redis取值
        foreach ($authorityIdArr as $authorityId) {
            try {
                $key = sprintf(CacheKey::TOKEN, trim($authorityId));
                LogFacade::info("DaemonTokenTask process. the key: " . $key);
                $this->refreshToken(RedisFacade::get($key));
            } catch (Exception $e) {
                LogFacade::err("DaemonTokenTask process refreshToken ex. authorityId:{$authorityId}, " . $e->getTraceAsString());
            }
        }

    }

    private function refreshToken($oldTokenStr)
    {
        LogFacade::info("DaemonTokenTask refreshToken. the oldTokenStr is: " . $oldTokenStr);

        if (empty($oldTokenStr) || !is_string($oldTokenStr)) {
            LogFacade::info("DaemonTokenTask refreshToken. the oldTokenStr valid. " . $oldTokenStr);
            return;
        }

        $oldTokenArr = json_decode($oldTokenStr, true);
        if (!is_array($oldTokenArr) || !array_key_exists('refresh_token', $oldTokenArr)) {
            LogFacade::info("DaemonTokenTask refreshToken. the oldTokenStr decode fail. " . $oldTokenStr);
            return;
        }

        $newTokenArr = (new Token(
            EnvFacade::get('opensdk.clientId'), EnvFacade::get('opensdk.clientSecret')
        ))->refreshToken($oldTokenArr['refresh_token']);

        LogFacade::info("DaemonTokenTask refreshToken. newTokenArr", $newTokenArr);

        if (is_array($newTokenArr) && array_key_exists('access_token', $newTokenArr)) {
            $key = sprintf(CacheKey::TOKEN, trim($newTokenArr['authority_id']));
            $setResp = RedisFacade::set($key, json_encode($newTokenArr));
            LogFacade::info("DaemonTokenTask refreshToken. redis set: {$setResp}", $newTokenArr);
        }
    }

}