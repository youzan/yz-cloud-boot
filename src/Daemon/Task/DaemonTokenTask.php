<?php

namespace YouzanCloudBoot\Daemon\Task;

use Exception;
use YouzanCloudBoot\Component\BaseComponent;
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

        $authorityIdArr = json_decode($authorityIds);
        if (!is_array($authorityIdArr)) {
            LogFacade::info("DaemonTokenTask process. the authorityIds decode fail. " . $authorityIds);
            return;
        }

        // 2. 遍历 从Redis取值
        foreach ($authorityIdArr as $authorityId) {
            try {
                $this->refreshToken(RedisFacade::get("yz_cloud_boot_token_" . $authorityId));
            } catch (Exception $e) {
                LogFacade::err("DaemonTokenTask process refreshToken ex. authorityId:{$authorityId}, " . $e->getTraceAsString());
            }
        }

    }

    private function refreshToken($oldTokenStr)
    {
        if (empty($oldTokenStr) || !is_string($oldTokenStr)) {
            LogFacade::info("DaemonTokenTask refreshToken. the oldTokenStr valid. " . $oldTokenStr);
            return;
        }

        $oldTokenArr = json_decode($oldTokenStr);
        if (!is_array($oldTokenArr) || !in_array('refresh_token', $oldTokenArr)) {
            LogFacade::info("DaemonTokenTask refreshToken. the oldTokenStr decode fail. " . $oldTokenStr);
            return;
        }

        $newTokenArr = (new \Youzan\Open\Token(
            EnvFacade::get('opensdk.clientId'), EnvFacade::get('opensdk.clientSecret')
        ))->getToken('refresh_token', $oldTokenArr);

        if (is_array($newTokenArr) && in_array('access_token', $newTokenArr)) {
            $setResp = RedisFacade::set('yz_cloud_boot_token_' . $newTokenArr['authority_id'], $newTokenArr);
            LogFacade::info("DaemonTokenTask refreshToken. set redis. {$setResp}, " . $newTokenArr);
        }
    }

}