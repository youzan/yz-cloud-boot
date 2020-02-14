<?php

namespace YouzanCloudBoot\Util;

use Exception;
use Throwable;
use Youzan\Open\Token;
use YouzanCloudBoot\Component\BaseComponent;
use YouzanCloudBoot\Facades\EnvFacade;
use YouzanCloudBoot\Facades\LogFacade;
use YouzanCloudBoot\Facades\RedisFacade;

class TokenUtil extends BaseComponent
{

    public function code2Token(string $code, $reties = 3): array
    {
        if ($reties < 0) {
            LogFacade::warn("TokenUtil code2Token. exceeds the maximum retries");
            return null;
        }

        try {
            return $this->code2TokenProcess($code);
        } catch (Throwable $e) {
            if ($reties > 0) {
                return $this->code2Token($code, --$reties);
            }
            LogFacade::err("TokenUtil code2Token. exception, code:{$code}, " . $e->getTraceAsString());
        }

        return null;
    }


    private function code2TokenProcess(string $code): array
    {
        $tokenArr = (new Token(
            EnvFacade::get('opensdk.clientId'), EnvFacade::get('opensdk.clientSecret')
        ))->getToken('authorization_code', ['code' => $code]);

        if (!is_array($tokenArr) || array_key_exists('access_token', $tokenArr)) {
            throw new Exception('newTokenArr valid');
        }

        $setResp = RedisFacade::set('yz_cloud_boot_token_' . $tokenArr['authority_id'], json_encode($tokenArr));
        LogFacade::info("TokenUtil code2TokenProcess. redis set: {$setResp}", $tokenArr);

        return $tokenArr;
    }

}