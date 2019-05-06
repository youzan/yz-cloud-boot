<?php

namespace YouzanCloudBoot\Http;

use YouzanCloudBoot\Component\BaseComponent;

class HttpClientFactory extends BaseComponent
{

    public function buildHttpClient(): HttpClientWrapper
    {
        $env = $this->getEnvUtil();

        $enable = $env->get('youzan.proxy.enable');

        if ($enable !== 'true') {
            $proxy = '';
        } else {
            $proxy = $env->get('youzan.proxy.host');
        }

        $token = $env->get('youzan.proxy.token');

        $nonProxyHosts = $env->get('youzan.proxy.nonProxyHosts');
        if ($nonProxyHosts) {
            $ignoreList = explode(',', $nonProxyHosts);
        } else {
            $ignoreList = [];
        }

        return new HttpClientWrapper($proxy, $token, $ignoreList, $this->getLog());
    }

}